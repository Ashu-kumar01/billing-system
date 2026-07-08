<x-app-layout>
    @php($breadcrumbs = ['Payments' => null])
    <x-page-header title="Payments" subtitle="Track customer receipts and supplier payments.">
        <x-slot name="actions">
            <a href="{{ route('payments.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Record Payment
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('payments.index') }}" class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:flex-wrap">
            <div class="relative flex-1 max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by transaction, customer, supplier..."
                    class="form-input pl-9"
                >
            </div>

            <select name="payment_method" class="form-select w-auto">
                <option value="">All Methods</option>
                @foreach (['cash', 'card', 'upi', 'bank_transfer', 'cheque', 'other'] as $method)
                    <option value="{{ $method }}" @selected(request('payment_method') === $method)>{{ ucwords(str_replace('_', ' ', $method)) }}</option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-auto" title="From date">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-auto" title="To date">

            <button type="submit" class="btn-secondary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if (request()->anyFilled(['search', 'payment_method', 'from', 'to']))
                <a href="{{ route('payments.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Clear
                </a>
            @endif
        </form>

        @if ($payments->isEmpty())
            <x-empty-state
                icon="fa-money-bill-wave"
                title="No payments found"
                subtitle="Record a payment against an invoice or purchase to get started."
            >
                <a href="{{ route('payments.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus"></i> Record Payment
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Party</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="text-muted">{{ $payment->payment_date->format('d M Y') }}</td>
                                <td>
                                    @if ($payment->invoice_id)
                                        <x-badge color="info">Receipt</x-badge>
                                    @else
                                        <x-badge color="warning">Payout</x-badge>
                                    @endif
                                </td>
                                <td class="font-medium">
                                    @if ($payment->invoice)
                                        {{ $payment->invoice->invoice_no }}
                                    @elseif ($payment->purchase)
                                        {{ $payment->purchase->invoice_no }}
                                    @else
                                        &mdash;
                                    @endif
                                </td>
                                <td>{{ $payment->customer->name ?? $payment->supplier->name ?? '—' }}</td>
                                <td class="font-semibold">₹{{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    @php($methodColors = ['cash' => 'success', 'card' => 'info', 'upi' => 'info', 'bank_transfer' => 'info', 'cheque' => 'warning', 'other' => 'muted'])
                                    <x-badge :color="$methodColors[$payment->payment_method] ?? 'muted'">
                                        {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                    </x-badge>
                                </td>
                                <td class="text-muted">{{ $payment->transaction_id ?: '—' }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('payments.show', $payment) }}" class="rounded-lg p-2 text-muted hover:bg-primary-50 hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <x-delete-form :action="route('payments.destroy', $payment)" label="this payment" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
