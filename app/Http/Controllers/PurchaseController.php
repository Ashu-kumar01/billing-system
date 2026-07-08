<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchases = Purchase::query()
            ->with('supplier')
            ->search($request->get('search'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->get('status')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('purchase_date', '>=', $request->get('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('purchase_date', '<=', $request->get('to')))
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('purchases.index', compact('purchases'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get(['id', 'name', 'company_name']);
        $products = Product::orderBy('name')->get(['id', 'name', 'sku', 'cost_price', 'selling_price', 'stock']);

        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $data = $request->validated();

        $purchase = DB::transaction(function () use ($data) {
            $storeId = auth()->user()->store_id ?? Store::first()?->id;

            $subtotal = 0;
            $totalDiscount = 0;
            $totalTax = 0;
            $lineItems = [];

            foreach ($data['items'] as $item) {
                $quantity = (float) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $discount = (float) ($item['discount'] ?? 0);
                $tax = (float) ($item['tax'] ?? 0);

                $lineSubtotal = ($quantity * $unitCost) - $discount + $tax;

                $subtotal += $quantity * $unitCost;
                $totalDiscount += $discount;
                $totalTax += $tax;

                $lineItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'discount' => $discount,
                    'tax' => $tax,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $total = $subtotal - $totalDiscount + $totalTax;
            $paidAmount = (float) ($data['paid_amount'] ?? 0);
            $dueAmount = $total - $paidAmount;

            $nextNumber = Purchase::count() + 1;
            $invoiceNo = 'PO-' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
            while (Purchase::where('invoice_no', $invoiceNo)->exists()) {
                $nextNumber++;
                $invoiceNo = 'PO-' . str_pad((string) $nextNumber, 5, '0', STR_PAD_LEFT);
            }

            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'store_id' => $storeId,
                'user_id' => auth()->id(),
                'invoice_no' => $invoiceNo,
                'purchase_date' => $data['purchase_date'],
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $totalTax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'note' => $data['note'] ?? null,
                'status' => $data['status'],
            ]);

            foreach ($lineItems as $line) {
                $purchase->items()->create($line);

                /** @var Product $product */
                $product = Product::findOrFail($line['product_id']);
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore + $line['quantity'];
                $product->update(['stock' => $stockAfter]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'store_id' => $storeId,
                    'user_id' => auth()->id(),
                    'type' => 'purchase',
                    'quantity' => $line['quantity'],
                    'quantity_change' => $line['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'reference_id' => $purchase->id,
                    'reference_type' => Purchase::class,
                    'note' => 'Stock received via purchase order ' . $purchase->invoice_no,
                ]);

                $productStock = ProductStock::firstOrNew([
                    'product_id' => $product->id,
                    'store_id' => $storeId,
                ]);
                $productStock->quantity = ($productStock->quantity ?? 0) + $line['quantity'];
                $productStock->available_quantity = ($productStock->available_quantity ?? 0) + $line['quantity'];
                $productStock->reserved_quantity = $productStock->reserved_quantity ?? 0;
                $productStock->save();
            }

            if ($paidAmount > 0) {
                Payment::create([
                    'purchase_id' => $purchase->id,
                    'supplier_id' => $purchase->supplier_id,
                    'user_id' => auth()->id(),
                    'amount' => $paidAmount,
                    'payment_method' => $data['payment_method'] ?? 'cash',
                    'payment_date' => $data['purchase_date'],
                    'note' => 'Initial payment on purchase creation',
                ]);
            }

            return $purchase;
        });

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'store', 'user', 'items.product', 'payments']);

        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = Supplier::orderBy('name')->get(['id', 'name', 'company_name']);

        return view('purchases.edit', compact('purchase', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        $purchase->update($request->validated());

        return redirect()->route('purchases.show', $purchase)
            ->with('success', 'Purchase order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        try {
            DB::transaction(function () use ($purchase) {
                $storeId = $purchase->store_id;

                foreach ($purchase->items as $item) {
                    /** @var Product $product */
                    $product = Product::find($item->product_id);

                    if ($product) {
                        $stockBefore = $product->stock;
                        $stockAfter = $stockBefore - $item->quantity;
                        $product->update(['stock' => $stockAfter]);

                        StockMovement::create([
                            'product_id' => $product->id,
                            'store_id' => $storeId,
                            'user_id' => auth()->id(),
                            'type' => 'adjustment',
                            'quantity' => $item->quantity,
                            'quantity_change' => -$item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'reference_id' => $purchase->id,
                            'reference_type' => Purchase::class,
                            'note' => 'Reversal due to deletion of purchase order ' . $purchase->invoice_no,
                        ]);

                        $productStock = ProductStock::where('product_id', $product->id)
                            ->where('store_id', $storeId)
                            ->first();

                        if ($productStock) {
                            $productStock->quantity = max(0, $productStock->quantity - $item->quantity);
                            $productStock->available_quantity = max(0, $productStock->available_quantity - $item->quantity);
                            $productStock->save();
                        }
                    }
                }

                $purchase->items()->delete();
                $purchase->payments()->delete();
                $purchase->delete();
            });
        } catch (\Throwable $e) {
            return redirect()->route('purchases.index')
                ->with('error', 'Failed to delete purchase order: ' . $e->getMessage());
        }

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase order deleted successfully.');
    }

    /**
     * Generate a printable PDF for the purchase order.
     */
    public function pdf(Purchase $purchase)
    {
        $purchase->load(['supplier', 'store', 'items.product']);

        return Pdf::loadView('purchases.pdf', compact('purchase'))
            ->download("purchase-{$purchase->invoice_no}.pdf");
    }
}
