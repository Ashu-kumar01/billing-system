<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with(['invoice', 'purchase', 'customer', 'supplier', 'user'])
            ->search($request->string('search')->toString())
            ->when($request->filled('payment_method'), fn ($q) => $q->where('payment_method', $request->string('payment_method')))
            ->when($request->filled('from'), fn ($q) => $q->whereDate('payment_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('payment_date', '<=', $request->date('to')))
            ->latest('payment_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $invoices = Invoice::query()->where('payment_status', '!=', 'paid')->with('customer')->latest()->get();
        $purchases = Purchase::query()->where('due_amount', '>', 0)->with('supplier')->latest()->get();
        $customers = Customer::query()->orderBy('name')->get();
        $suppliers = Supplier::query()->orderBy('name')->get();

        return view('payments.create', compact('invoices', 'purchases', 'customers', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['invoice_id'])) {
            $invoice = Invoice::findOrFail($data['invoice_id']);

            if ($data['amount'] > $invoice->due_amount) {
                return back()->withInput()->withErrors([
                    'amount' => 'Amount cannot exceed the invoice due amount of ₹'.number_format($invoice->due_amount, 2).'.',
                ]);
            }
        }

        if (! empty($data['purchase_id'])) {
            $purchase = Purchase::findOrFail($data['purchase_id']);

            if ($data['amount'] > $purchase->due_amount) {
                return back()->withInput()->withErrors([
                    'amount' => 'Amount cannot exceed the purchase due amount of ₹'.number_format($purchase->due_amount, 2).'.',
                ]);
            }
        }

        DB::transaction(function () use ($data) {
            $data['user_id'] = auth()->id();

            if (! empty($data['invoice_id'])) {
                $invoice = Invoice::findOrFail($data['invoice_id']);
                $data['customer_id'] = $data['customer_id'] ?? $invoice->customer_id;
            }

            if (! empty($data['purchase_id'])) {
                $purchase = Purchase::findOrFail($data['purchase_id']);
                $data['supplier_id'] = $data['supplier_id'] ?? $purchase->supplier_id;
            }

            Payment::create($data);

            if (! empty($data['invoice_id'])) {
                $invoice = Invoice::lockForUpdate()->findOrFail($data['invoice_id']);
                $invoice->paid_amount += $data['amount'];
                $invoice->due_amount = max(0, $invoice->total - $invoice->paid_amount);

                if ($invoice->due_amount <= 0) {
                    $invoice->payment_status = 'paid';
                } elseif ($invoice->paid_amount > 0) {
                    $invoice->payment_status = 'partial';
                } else {
                    $invoice->payment_status = 'due';
                }

                $invoice->save();
            }

            if (! empty($data['purchase_id'])) {
                $purchase = Purchase::lockForUpdate()->findOrFail($data['purchase_id']);
                $purchase->paid_amount += $data['amount'];
                $purchase->due_amount = max(0, $purchase->total - $purchase->paid_amount);
                $purchase->save();
            }
        });

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['invoice', 'purchase', 'customer', 'supplier', 'user']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        try {
            DB::transaction(function () use ($payment) {
                if ($payment->invoice_id) {
                    $invoice = Invoice::lockForUpdate()->find($payment->invoice_id);

                    if ($invoice) {
                        $invoice->paid_amount = max(0, $invoice->paid_amount - $payment->amount);
                        $invoice->due_amount = max(0, $invoice->total - $invoice->paid_amount);

                        if ($invoice->due_amount <= 0) {
                            $invoice->payment_status = 'paid';
                        } elseif ($invoice->paid_amount > 0) {
                            $invoice->payment_status = 'partial';
                        } else {
                            $invoice->payment_status = 'due';
                        }

                        $invoice->save();
                    }
                }

                if ($payment->purchase_id) {
                    $purchase = Purchase::lockForUpdate()->find($payment->purchase_id);

                    if ($purchase) {
                        $purchase->paid_amount = max(0, $purchase->paid_amount - $payment->amount);
                        $purchase->due_amount = max(0, $purchase->total - $purchase->paid_amount);
                        $purchase->save();
                    }
                }

                $payment->delete();
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Unable to delete this payment. Please try again.');
        }

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
