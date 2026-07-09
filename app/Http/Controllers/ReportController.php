<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    protected function dateRange(Request $request): array
    {
        $from = $request->get('from') ?: Carbon::now()->startOfMonth()->format('Y-m-d');
        $to = $request->get('to') ?: Carbon::now()->endOfMonth()->format('Y-m-d');

        return [$from, $to];
    }

    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $invoices = Invoice::with('customer')
            ->whereBetween('invoice_date', [$from, $to])
            ->orderByDesc('invoice_date')
            ->get();

        $totalSales = $invoices->sum('total');
        $totalTax = $invoices->sum('tax');
        $totalDiscount = $invoices->sum('discount');
        $count = $invoices->count();

        return view('reports.sales', compact('invoices', 'from', 'to', 'totalSales', 'totalTax', 'totalDiscount', 'count'));
    }

    public function purchases(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $purchases = Purchase::with('supplier')
            ->whereBetween('purchase_date', [$from, $to])
            ->orderByDesc('purchase_date')
            ->get();

        $totalPurchases = $purchases->sum('total');
        $totalTax = $purchases->sum('tax');
        $totalDiscount = $purchases->sum('discount');
        $count = $purchases->count();

        return view('reports.purchases', compact('purchases', 'from', 'to', 'totalPurchases', 'totalTax', 'totalDiscount', 'count'));
    }

    public function expenses(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$from, $to])
            ->orderByDesc('expense_date')
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $count = $expenses->count();

        $byCategory = $expenses->groupBy('category')->map(fn ($items) => $items->sum('amount'));

        return view('reports.expenses', compact('expenses', 'from', 'to', 'totalExpenses', 'count', 'byCategory'));
    }

    public function customers(Request $request)
    {
        $sort = $request->get('sort', 'due_desc');

        $customers = Customer::withSum('invoices as total_invoiced', 'total')
            ->withSum('invoices as total_paid', 'paid_amount')
            ->withSum('invoices as total_due', 'due_amount')
            ->get()
            ->map(function ($customer) {
                $customer->total_invoiced = $customer->total_invoiced ?? 0;
                $customer->total_paid = $customer->total_paid ?? 0;
                $customer->total_due = ($customer->total_due ?? 0) + $customer->opening_balance;

                return $customer;
            });

        $customers = $sort === 'due_asc'
            ? $customers->sortBy('total_due')->values()
            : $customers->sortByDesc('total_due')->values();

        return view('reports.customers', compact('customers', 'sort'));
    }

    public function profitLoss(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $totalSales = Invoice::whereBetween('invoice_date', [$from, $to])->sum('total');

        $costOfGoodsSold = (float) InvoiceItem::query()
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('products', 'products.id', '=', 'invoice_items.product_id')
            ->whereBetween('invoices.invoice_date', [$from, $to])
            ->selectRaw('SUM(invoice_items.quantity * products.cost_price) as cogs')
            ->value('cogs') ?? 0;

        $expenses = Expense::whereBetween('expense_date', [$from, $to])->get();
        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category')->map(fn ($items) => $items->sum('amount'));
        $netProfit = $totalSales - $costOfGoodsSold - $totalExpenses;

        return view('reports.profit-loss', compact('from', 'to', 'totalSales', 'costOfGoodsSold', 'totalExpenses', 'expensesByCategory', 'netProfit'));
    }

    public function exportSales(Request $request)
    {
        [$from, $to] = $this->dateRange($request);

        $invoices = Invoice::with('customer')
            ->whereBetween('invoice_date', [$from, $to])
            ->orderByDesc('invoice_date')
            ->get();

        $filename = 'sales-report.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->streamDownload(function () use ($invoices) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Invoice No', 'Date', 'Customer', 'Subtotal', 'Discount', 'Tax', 'Total', 'Paid', 'Due', 'Status']);

            foreach ($invoices as $invoice) {
                fputcsv($handle, [
                    $invoice->invoice_no,
                    optional($invoice->invoice_date)->format('Y-m-d'),
                    $invoice->customer->name ?? '—',
                    $invoice->subtotal,
                    $invoice->discount,
                    $invoice->tax,
                    $invoice->total,
                    $invoice->paid_amount,
                    $invoice->due_amount,
                    $invoice->payment_status,
                ]);
            }

            fclose($handle);
        }, $filename, $headers);
    }
}
