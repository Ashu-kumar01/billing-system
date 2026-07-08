<x-app-layout>
    <div class="glass relative mb-6 overflow-hidden p-6 sm:p-8">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 opacity-10"></div>
        <div class="relative flex flex-col items-center gap-6 sm:flex-row sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-primary-600">Dashboard</p>
                <h1 class="mt-1 text-2xl font-bold text-ink sm:text-3xl">Welcome back, {{ auth()->user()->name }} 👋</h1>
                <p class="mt-2 max-w-lg text-sm text-muted">Here's what's happening across your business today.</p>
            </div>
            <x-illustration type="dashboard" class="hidden w-40 h-40 sm:block" />
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total Sales" value="₹{{ number_format($totalSales, 2) }}" icon="fa-sack-dollar" color="primary" :trend="number_format($salesGrowth, 1).'%'" :trendUp="$salesGrowth >= 0" />
        <x-stat-card label="Today's Sales" value="₹{{ number_format($todaySales, 2) }}" icon="fa-calendar-day" color="sky" />
        <x-stat-card label="Monthly Sales" value="₹{{ number_format($monthlySales, 2) }}" icon="fa-calendar-check" color="mint" />
        <x-stat-card label="Pending Payments" value="₹{{ number_format($pendingPayments, 2) }}" icon="fa-hourglass-half" color="orange" />
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Paid Invoices" value="{{ $paidInvoicesCount }}" icon="fa-file-circle-check" color="success" />
        <x-stat-card label="Customers" value="{{ $totalCustomers }}" icon="fa-users" color="pink" />
        <x-stat-card label="Products" value="{{ $totalProducts }}" icon="fa-box" color="purple" />
        <x-stat-card label="Profit" value="₹{{ number_format($profit, 2) }}" icon="fa-chart-pie" :color="$profit >= 0 ? 'success' : 'danger'" />
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-ink">Sales vs Expenses</h3>
                <span class="text-xs text-muted">Last 6 months</span>
            </div>
            <canvas id="salesExpensesChart" height="110"></canvas>
        </div>

        <div class="card p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-ink">Invoice Status</h3>
            </div>
            <canvas id="paymentStatusChart" height="180"></canvas>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-2">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-ink">Weekly Sales</h3>
                <span class="text-xs text-muted">Last 7 days</span>
            </div>
            <canvas id="weeklySalesChart" height="110"></canvas>
        </div>

        <div class="card p-5">
            <h3 class="mb-4 text-base font-semibold text-ink">Low Stock Alerts</h3>
            <div class="space-y-3">
                @forelse ($lowStockProducts as $product)
                    <div class="flex items-center justify-between rounded-xl bg-surface-muted px-3 py-2.5">
                        <div>
                            <p class="text-sm font-medium text-ink">{{ $product->name }}</p>
                            <p class="text-xs text-muted">SKU: {{ $product->sku }}</p>
                        </div>
                        <x-badge color="danger">{{ $product->stock }} left</x-badge>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-muted">All products are well stocked.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="card p-5 lg:col-span-2">
            <div class="mb-3 flex items-center justify-between">
                <h3 class="text-base font-semibold text-ink">Recent Invoices</h3>
                <a href="{{ route('invoices.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentInvoices as $invoice)
                            <tr>
                                <td class="font-medium">{{ $invoice->invoice_no }}</td>
                                <td>{{ $invoice->customer->name ?? '—' }}</td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td>₹{{ number_format($invoice->total, 2) }}</td>
                                <td>
                                    <x-badge :color="['paid' => 'success', 'partial' => 'warning', 'due' => 'danger'][$invoice->payment_status]">
                                        {{ ucfirst($invoice->payment_status) }}
                                    </x-badge>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-6">No invoices yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card p-5">
            <h3 class="mb-3 text-base font-semibold text-ink">Recent Customers</h3>
            <div class="space-y-3">
                @forelse ($recentCustomers as $customer)
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-50 text-sm font-semibold text-primary-700">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-ink">{{ $customer->name }}</p>
                            <p class="truncate text-xs text-muted">{{ $customer->email ?? $customer->phone }}</p>
                        </div>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-muted">No customers yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const trend = @json($salesTrend);
                const weekly = @json($weeklySales);
                const status = @json($paymentStatusBreakdown);

                new Chart(document.getElementById('salesExpensesChart'), {
                    type: 'line',
                    data: {
                        labels: trend.map(t => t.label),
                        datasets: [
                            { label: 'Sales', data: trend.map(t => t.sales), borderColor: '#4F46E5', backgroundColor: 'rgba(79,70,229,0.10)', tension: 0.4, fill: true },
                            { label: 'Expenses', data: trend.map(t => t.expenses), borderColor: '#EC4899', backgroundColor: 'rgba(236,72,153,0.08)', tension: 0.4, fill: true },
                        ],
                    },
                    options: { plugins: { legend: { position: 'bottom' } }, scales: { y: { beginAtZero: true } } },
                });

                new Chart(document.getElementById('paymentStatusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(status).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
                        datasets: [{ data: Object.values(status), backgroundColor: ['#10B981', '#FB923C', '#EF4444'] }],
                    },
                    options: { plugins: { legend: { position: 'bottom' } } },
                });

                new Chart(document.getElementById('weeklySalesChart'), {
                    type: 'bar',
                    data: {
                        labels: weekly.map(w => w.label),
                        datasets: [{ label: 'Sales', data: weekly.map(w => w.total), backgroundColor: '#06B6D4', borderRadius: 6 }],
                    },
                    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } },
                });
            });
        </script>
    @endpush
</x-app-layout>
