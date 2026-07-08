<x-app-layout>
    <x-breadcrumb :items="['Reports' => route('reports.index'), 'Expenses Report' => null]" />

    <x-page-header title="Expenses Report" subtitle="Expense totals and category breakdown for the selected date range." />

    <div class="card mb-6 p-5">
        <form method="GET" action="{{ route('reports.expenses') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-input">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('reports.expenses') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
        <x-stat-card label="Total Expenses" :value="'₹'.number_format($totalExpenses, 2)" icon="fa-receipt" color="danger" />
        <x-stat-card label="Number of Expenses" :value="$count" icon="fa-list" color="secondary" />
    </div>

    <div class="mb-6 card p-5">
        <h2 class="mb-4 text-lg font-semibold text-ink">Breakdown by Category</h2>
        @if ($byCategory->isEmpty())
            <x-empty-state icon="fa-chart-pie" title="No data" subtitle="No expenses recorded for this date range." />
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="space-y-3">
                    @foreach ($byCategory as $category => $amount)
                        @php $percent = $totalExpenses > 0 ? round(($amount / $totalExpenses) * 100, 1) : 0; @endphp
                        <div>
                            <div class="mb-1 flex items-center justify-between text-sm">
                                <span class="font-medium text-ink">{{ ucfirst($category) }}</span>
                                <span class="text-muted">₹{{ number_format($amount, 2) }} ({{ $percent }}%)</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-surface-soft">
                                <div class="h-2 rounded-full bg-primary-600" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div>
                    <canvas id="expenseCategoryChart" height="220"></canvas>
                </div>
            </div>
        @endif
    </div>

    <div class="card p-5">
        @if ($expenses->isEmpty())
            <x-empty-state icon="fa-receipt" title="No expenses found" subtitle="No expenses recorded for this date range." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Recorded By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td>{{ optional($expense->expense_date)->format('d M Y') }}</td>
                                <td class="font-medium text-ink">{{ $expense->title }}</td>
                                <td><span class="badge-info">{{ ucfirst($expense->category) }}</span></td>
                                <td>₹{{ number_format($expense->amount, 2) }}</td>
                                <td>{{ $expense->user->name ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if ($byCategory->isNotEmpty())
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('expenseCategoryChart');
                if (ctx && window.Chart) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($byCategory->keys()->map(fn($c) => ucfirst($c))->values()) !!},
                            datasets: [{
                                label: 'Amount',
                                data: {!! json_encode($byCategory->values()) !!},
                                backgroundColor: '#2563EB',
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true } }
                        }
                    });
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>
