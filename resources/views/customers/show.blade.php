<x-app-layout>
    <x-breadcrumb :items="['Customers' => route('customers.index'), $customer->name => null]" />

    <x-page-header title="{{ $customer->name }}" subtitle="Customer profile, invoice history and payments.">
        <x-slot name="actions">
            <a href="{{ route('customers.edit', $customer) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="{{ route('customers.index') }}" class="btn-primary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-stat-card label="Outstanding Balance" :value="'₹'.number_format($outstandingBalance, 2)" icon="fa-wallet" color="danger" />
        <x-stat-card label="Opening Balance" :value="'₹'.number_format($customer->opening_balance, 2)" icon="fa-scale-balanced" color="secondary" />
        <x-stat-card label="Total Invoices" :value="$customer->invoices()->count()" icon="fa-file-invoice" color="primary" />
    </div>

    <div class="mb-6 card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Contact Information</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs font-medium uppercase text-muted">Email</p>
                <p class="mt-1 text-sm text-ink">{{ $customer->email ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Phone</p>
                <p class="mt-1 text-sm text-ink">{{ $customer->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Status</p>
                <p class="mt-1">
                    @if ($customer->status)
                        <span class="badge-success">Active</span>
                    @else
                        <span class="badge-muted">Inactive</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Address</p>
                <p class="mt-1 text-sm text-ink">{{ $customer->address ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">City / State</p>
                <p class="mt-1 text-sm text-ink">{{ collect([$customer->city, $customer->state])->filter()->implode(', ') ?: '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Country / Zipcode</p>
                <p class="mt-1 text-sm text-ink">{{ collect([$customer->country, $customer->zipcode])->filter()->implode(', ') ?: '—' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-6 card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Recent Invoices</h2>
        @if ($invoices->isEmpty())
            <x-empty-state icon="fa-file-invoice" title="No invoices yet" subtitle="This customer has no invoices recorded." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Invoice No</th>
                            <th>Date</th>
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
                                <td>₹{{ number_format($invoice->total, 2) }}</td>
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
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <div class="card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Payments</h2>
        @if ($payments->isEmpty())
            <x-empty-state icon="fa-money-bill-wave" title="No payments yet" subtitle="This customer has no payments recorded." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td>{{ optional($payment->payment_date)->format('d M Y') }}</td>
                                <td>₹{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->payment_method ?? '—' }}</td>
                                <td>{{ $payment->transaction_id ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
