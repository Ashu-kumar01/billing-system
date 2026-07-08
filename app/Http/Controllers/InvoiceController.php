<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::query()
            ->search($request->get('search'))
            ->when($request->get('status'), fn ($q, $status) => $q->where('payment_status', $status))
            ->when($request->get('from'), fn ($q, $from) => $q->where('invoice_date', '>=', $from))
            ->when($request->get('to'), fn ($q, $to) => $q->where('invoice_date', '<=', $to))
            ->with('customer')
            ->latest('invoice_date')
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('status', true)->orderBy('name')->get();

        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();

        $invoice = DB::transaction(function () use ($data) {
            $storeId = auth()->user()->store_id ?? Store::first()?->id;
            $counter = Counter::where('store_id', $storeId)->first();

            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = (float) ($data['discount'] ?? 0);

            $lineItems = [];
            foreach ($data['items'] as $item) {
                $qty = (float) $item['quantity'];
                $price = (float) $item['unit_price'];
                $lineDiscount = (float) ($item['discount'] ?? 0);
                $lineTax = (float) ($item['tax'] ?? 0);
                $lineSubtotal = ($qty * $price) - $lineDiscount + $lineTax;

                $subtotal += $qty * $price;
                $totalTax += $lineTax;

                $lineItems[] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $lineDiscount,
                    'tax' => $lineTax,
                    'subtotal' => $lineSubtotal,
                ];
            }

            $total = $subtotal - $totalDiscount + $totalTax;
            $paidAmount = min((float) ($data['paid_amount'] ?? 0), $total);
            $dueAmount = $total - $paidAmount;

            $paymentStatus = $dueAmount <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : 'due');

            $invoicePrefix = Setting::get('invoice_prefix', 'INV-');
            $nextNumber = str_pad((string) (Invoice::count() + 1), 5, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'customer_id' => $data['customer_id'] ?? null,
                'store_id' => $storeId,
                'counter_id' => $counter?->id,
                'user_id' => auth()->id(),
                'invoice_no' => $invoicePrefix.$nextNumber,
                'invoice_date' => $data['invoice_date'],
                'subtotal' => $subtotal,
                'discount' => $totalDiscount,
                'tax' => $totalTax,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'payment_status' => $paymentStatus,
                'note' => $data['note'] ?? null,
            ]);

            foreach ($lineItems as $line) {
                InvoiceItem::create(array_merge($line, ['invoice_id' => $invoice->id]));

                $product = Product::find($line['product_id']);
                $stockBefore = $product->stock;
                $stockAfter = $stockBefore - $line['quantity'];
                $product->update(['stock' => max($stockAfter, 0)]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'store_id' => $storeId,
                    'user_id' => auth()->id(),
                    'type' => 'sale',
                    'quantity' => $line['quantity'],
                    'quantity_change' => -$line['quantity'],
                    'stock_before' => $stockBefore,
                    'stock_after' => max($stockAfter, 0),
                    'reference_id' => $invoice->id,
                    'reference_type' => Invoice::class,
                ]);

                $productStock = ProductStock::firstOrCreate(
                    ['product_id' => $product->id, 'store_id' => $storeId],
                    ['quantity' => 0, 'reserved_quantity' => 0, 'available_quantity' => 0]
                );
                $productStock->increment('quantity', -$line['quantity']);
                $productStock->increment('available_quantity', -$line['quantity']);
            }

            if ($paidAmount > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'user_id' => auth()->id(),
                    'amount' => $paidAmount,
                    'payment_method' => 'cash',
                    'payment_date' => $invoice->invoice_date,
                    'note' => 'Initial payment on invoice creation',
                ]);
            }

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer', 'payments', 'user');

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::orderBy('name')->get();

        return view('invoices.edit', compact('invoice', 'customers'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        try {
            DB::transaction(function () use ($invoice) {
                $storeId = $invoice->store_id;

                foreach ($invoice->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $stockBefore = $product->stock;
                        $stockAfter = $stockBefore + $item->quantity;
                        $product->update(['stock' => $stockAfter]);

                        StockMovement::create([
                            'product_id' => $product->id,
                            'store_id' => $storeId,
                            'user_id' => auth()->id(),
                            'type' => 'adjustment',
                            'quantity' => $item->quantity,
                            'quantity_change' => $item->quantity,
                            'stock_before' => $stockBefore,
                            'stock_after' => $stockAfter,
                            'reference_id' => $invoice->id,
                            'reference_type' => Invoice::class,
                            'note' => 'Reversed due to invoice deletion',
                        ]);
                    }
                }

                $invoice->payments()->delete();
                $invoice->items()->delete();
                $invoice->delete();
            });

            return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete this invoice.');
        }
    }

    public function print(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer');

        return view('invoices.print', compact('invoice'));
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load('items.product', 'customer');

        $pdf = Pdf::loadView('invoices.print', compact('invoice'))->setPaper('a4');

        return $pdf->download("invoice-{$invoice->invoice_no}.pdf");
    }
}
