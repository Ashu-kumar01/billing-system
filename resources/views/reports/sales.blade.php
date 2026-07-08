<x-app-layout>
    <x-breadcrumb :items="['Reports' => route('reports.index'), 'Sales Report' => null]" />

    <x-page-header title="Sales Report" subtitle="Invoice totals for the selected date range.">
        <x-slot name="actions">
            <a href="{{ route('reports.sales.export', request()->only('from', 'to')) }}" class="btn-secondary">
                <i class="fa-solid fa-file-csv"></i> Export CSV
            </a>
        </x-slot>
    </x-page-header>

    <div class="card mb-6 p-5">
        <form method="GET" action="{{ route('reports.sales') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-input">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('reports.sales') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total Sales" :value="'₹'.number_format($totalSales, 2)" icon="fa-sack-dollar" color="primary" />
        <x-stat-card label="Tax Collected" :value="'₹'.number_format($totalTax, 2)" icon="fa-percent" color="secondary" />
        <x-stat-card label="Discount Given" :value="'₹'.number_format($totalDiscount, 2)" icon="fa-tags" color="warning" />
        <x-stat-card label="Invoices" :value="$count" icon="fa-file-invoice" color="success" />
    </div>

    <div class="card p-5">
        @if ($invoices->isEmpty())
            <x-empty-state icon="fa-file-invoice-dollar" title="No invoices found" subtitle="No sales recorded for this date range." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Customer</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td class="font-medium text-ink">{{ $invoice->invoice_no }}</td>
                                <td>{{ optional($invoice->invoice_date)->format('d M Y') }}</td>
                                <td>{{ $invoice->customer->name ?? '—' }}</td>
                                <td>₹{{ number_format($invoice->subtotal, 2) }}</td>
                                <td>₹{{ number_format($invoice->discount, 2) }}</td>
                                <td>₹{{ number_format($invoice->tax, 2) }}</td>
                                <td class="font-medium text-ink">₹{{ number_format($invoice->total, 2) }}</td>
                                <td>₹{{ number_format($invoice->paid_amount, 2) }}</td>
                                <td>₹{{ number_format($invoice->due_amount, 2) }}</td>
                                <td>
                                    @php
                                        $badge = match ($invoice->payment_status) {
                                            'paid' => 'badge-success',
                                            'partial' => 'badge-warning',
                                            default => 'badge-danger',
                                        };
                                    @endphp
                                    <span class="{{ $badge }}">{{ ucfirst($invoice->payment_status ?? 'due') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
