<x-app-layout>
    <x-breadcrumb :items="['Reports' => route('reports.index'), 'Profit & Loss' => null]" />

    <x-page-header title="Profit &amp; Loss" subtitle="Revenue vs expenses for the selected date range." />

    <div class="card mb-6 p-5">
        <form method="GET" action="{{ route('reports.profit-loss') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-input">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('reports.profit-loss') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-stat-card label="Revenue" :value="'₹'.number_format($totalSales, 2)" icon="fa-sack-dollar" color="success" />
        <x-stat-card label="Expenses" :value="'₹'.number_format($totalExpenses, 2)" icon="fa-receipt" color="danger" />
        <x-stat-card label="Net Profit" :value="'₹'.number_format($netProfit, 2)" icon="fa-scale-balanced" :color="$netProfit >= 0 ? 'primary' : 'danger'" />
    </div>

    <div class="card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Statement for {{ \Illuminate\Support\Carbon::parse($from)->format('d M Y') }} - {{ \Illuminate\Support\Carbon::parse($to)->format('d M Y') }}</h2>

        <div class="divide-y divide-border">
            <div class="flex items-center justify-between py-3">
                <span class="font-medium text-ink">Revenue (Sales)</span>
                <span class="font-semibold text-success">₹{{ number_format($totalSales, 2) }}</span>
            </div>

            <div class="py-3">
                <p class="mb-2 font-medium text-ink">Expenses</p>
                @forelse ($expensesByCategory as $category => $amount)
                    <div class="flex items-center justify-between py-1 pl-4 text-sm">
                        <span class="text-muted">{{ ucfirst($category) }}</span>
                        <span class="text-ink">₹{{ number_format($amount, 2) }}</span>
                    </div>
                @empty
                    <p class="pl-4 text-sm text-muted">No expenses recorded.</p>
                @endforelse
                <div class="mt-2 flex items-center justify-between border-t border-border pt-2 pl-4 text-sm font-semibold">
                    <span class="text-ink">Total Expenses</span>
                    <span class="text-danger">₹{{ number_format($totalExpenses, 2) }}</span>
                </div>
            </div>

            <div class="flex items-center justify-between py-3">
                <span class="text-lg font-bold text-ink">Net Profit</span>
                <span class="text-lg font-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">₹{{ number_format($netProfit, 2) }}</span>
            </div>
        </div>
    </div>
</x-app-layout>
