<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonthNoOverflow()->endOfMonth();

        $totalSales = Invoice::sum('total');
        $todaySales = Invoice::whereDate('invoice_date', $today)->sum('total');
        $monthlySales = Invoice::where('invoice_date', '>=', $monthStart)->sum('total');
        $lastMonthSales = Invoice::whereBetween('invoice_date', [$lastMonthStart, $lastMonthEnd])->sum('total');
        $salesGrowth = $lastMonthSales > 0 ? (($monthlySales - $lastMonthSales) / $lastMonthSales) * 100 : 0;

        $pendingPayments = Invoice::whereIn('payment_status', ['due', 'partial'])->sum('due_amount');
        $paidInvoicesCount = Invoice::where('payment_status', 'paid')->count();

        $totalCustomers = Customer::count();
        $totalProducts = Product::count();

        $monthlyExpenses = Expense::where('expense_date', '>=', $monthStart)->sum('amount');
        $totalExpenses = Expense::sum('amount');
        $profit = $totalSales - $totalExpenses;

        $salesTrend = collect(range(5, 0))->map(function ($i) {
            $month = Carbon::now()->subMonths($i);

            return [
                'label' => $month->format('M Y'),
                'sales' => (float) Invoice::whereYear('invoice_date', $month->year)
                    ->whereMonth('invoice_date', $month->month)
                    ->sum('total'),
                'expenses' => (float) Expense::whereYear('expense_date', $month->year)
                    ->whereMonth('expense_date', $month->month)
                    ->sum('amount'),
            ];
        })->values();

        $paymentStatusBreakdown = Invoice::select('payment_status', DB::raw('count(*) as total'))
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $weeklySales = collect(range(6, 0))->map(function ($i) {
            $day = Carbon::today()->subDays($i);

            return [
                'label' => $day->format('D'),
                'total' => (float) Invoice::whereDate('invoice_date', $day)->sum('total'),
            ];
        })->values();

        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();
        $recentCustomers = Customer::latest()->take(5)->get();
        $recentPayments = Payment::with(['customer', 'supplier'])->latest()->take(5)->get();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'alert_quantity')->take(5)->get();

        return view('dashboard', compact(
            'totalSales', 'todaySales', 'monthlySales', 'salesGrowth', 'pendingPayments',
            'paidInvoicesCount', 'totalCustomers', 'totalProducts', 'monthlyExpenses',
            'totalExpenses', 'profit', 'salesTrend', 'paymentStatusBreakdown', 'weeklySales',
            'recentInvoices', 'recentCustomers', 'recentPayments', 'lowStockProducts'
        ));
    }
}
