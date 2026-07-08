<x-app-layout>
    <x-breadcrumb :items="['Reports' => null]" />

    <x-page-header title="Reports" subtitle="Analyze sales, purchases, expenses and business performance." />

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('reports.sales') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-lg text-primary-600">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">Sales Report</h3>
            <p class="mt-1 text-sm text-muted">Invoice totals, tax and discounts for a date range.</p>
        </a>

        <a href="{{ route('reports.purchases') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-lg text-secondary">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">Purchases Report</h3>
            <p class="mt-1 text-sm text-muted">Supplier purchase totals for a date range.</p>
        </a>

        <a href="{{ route('reports.expenses') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-lg text-danger">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">Expenses Report</h3>
            <p class="mt-1 text-sm text-muted">Expense breakdown by category.</p>
        </a>

        <a href="{{ route('reports.customers') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-lg text-warning">
                <i class="fa-solid fa-users"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">Customers Report</h3>
            <p class="mt-1 text-sm text-muted">Outstanding balances and invoiced totals per customer.</p>
        </a>

        <a href="{{ route('reports.sales') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-50 text-lg text-success">
                <i class="fa-solid fa-percent"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">GST / Tax Report</h3>
            <p class="mt-1 text-sm text-muted">Tax collected on sales, grouped from the sales report.</p>
        </a>

        <a href="{{ route('reports.profit-loss') }}" class="card p-6 transition-transform duration-200 hover:-translate-y-0.5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-50 text-lg text-primary-600">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <h3 class="mt-4 text-lg font-semibold text-ink">Profit &amp; Loss</h3>
            <p class="mt-1 text-sm text-muted">Revenue vs expenses and net profit for a date range.</p>
        </a>
    </div>
</x-app-layout>
