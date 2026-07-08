<x-app-layout>
    <x-breadcrumb :items="['Reports' => route('reports.index'), 'Purchases Report' => null]" />

    <x-page-header title="Purchases Report" subtitle="Purchase totals for the selected date range." />

    <div class="card mb-6 p-5">
        <form method="GET" action="{{ route('reports.purchases') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div>
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-input">
            </div>
            <div>
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-input">
            </div>
            <button type="submit" class="btn-primary">Filter</button>
            <a href="{{ route('reports.purchases') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-stat-card label="Total Purchases" :value="'₹'.number_format($totalPurchases, 2)" icon="fa-truck-ramp-box" color="primary" />
        <x-stat-card label="Tax Paid" :value="'₹'.number_format($totalTax, 2)" icon="fa-percent" color="secondary" />
        <x-stat-card label="Discount Received" :value="'₹'.number_format($totalDiscount, 2)" icon="fa-tags" color="warning" />
        <x-stat-card label="Purchases" :value="$count" icon="fa-file-invoice" color="success" />
    </div>

    <div class="card p-5">
        @if ($purchases->isEmpty())
            <x-empty-state icon="fa-truck-ramp-box" title="No purchases found" subtitle="No purchases recorded for this date range." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Supplier</th>
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
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td class="font-medium text-ink">{{ $purchase->invoice_no }}</td>
                                <td>{{ optional($purchase->purchase_date)->format('d M Y') }}</td>
                                <td>{{ $purchase->supplier->name ?? '—' }}</td>
                                <td>₹{{ number_format($purchase->subtotal, 2) }}</td>
                                <td>₹{{ number_format($purchase->discount, 2) }}</td>
                                <td>₹{{ number_format($purchase->tax, 2) }}</td>
                                <td class="font-medium text-ink">₹{{ number_format($purchase->total, 2) }}</td>
                                <td>₹{{ number_format($purchase->paid_amount, 2) }}</td>
                                <td>₹{{ number_format($purchase->due_amount, 2) }}</td>
                                <td><span class="badge-info">{{ ucfirst($purchase->status ?? '—') }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
