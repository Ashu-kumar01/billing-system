<x-app-layout>
    <x-breadcrumb :items="['Suppliers' => route('suppliers.index'), $supplier->name => null]" />

    <x-page-header title="{{ $supplier->name }}" subtitle="Supplier profile, purchase history and payments.">
        <x-slot name="actions">
            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="{{ route('suppliers.index') }}" class="btn-primary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-stat-card label="Outstanding Balance" :value="'₹'.number_format($outstandingBalance, 2)" icon="fa-wallet" color="danger" />
        <x-stat-card label="Total Purchases" :value="$supplier->purchases()->count()" icon="fa-truck" color="primary" />
        <x-stat-card label="Total Products" :value="$supplier->products()->count()" icon="fa-boxes-stacked" color="secondary" />
    </div>

    <div class="mb-6 card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Contact Information</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs font-medium uppercase text-muted">Company Name</p>
                <p class="mt-1 text-sm text-ink">{{ $supplier->company_name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Email</p>
                <p class="mt-1 text-sm text-ink">{{ $supplier->email ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Phone</p>
                <p class="mt-1 text-sm text-ink">{{ $supplier->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">GST Number</p>
                <p class="mt-1 text-sm text-ink">{{ $supplier->gst_number ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Status</p>
                <p class="mt-1">
                    @if ($supplier->status)
                        <span class="badge-success">Active</span>
                    @else
                        <span class="badge-muted">Inactive</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Address</p>
                <p class="mt-1 text-sm text-ink">{{ $supplier->address ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">City / State</p>
                <p class="mt-1 text-sm text-ink">{{ collect([$supplier->city, $supplier->state])->filter()->implode(', ') ?: '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Country / Zipcode</p>
                <p class="mt-1 text-sm text-ink">{{ collect([$supplier->country, $supplier->zipcode])->filter()->implode(', ') ?: '—' }}</p>
            </div>
        </div>
    </div>

    <div class="mb-6 card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Recent Purchases</h2>
        @if ($purchases->isEmpty())
            <x-empty-state icon="fa-truck" title="No purchases yet" subtitle="This supplier has no purchases recorded." />
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td class="font-medium text-ink">{{ $purchase->invoice_no }}</td>
                                <td>{{ optional($purchase->purchase_date)->format('d M Y') }}</td>
                                <td>₹{{ number_format($purchase->total, 2) }}</td>
                                <td>₹{{ number_format($purchase->paid_amount, 2) }}</td>
                                <td>₹{{ number_format($purchase->due_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

    <div class="card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Payments</h2>
        @if ($payments->isEmpty())
            <x-empty-state icon="fa-money-bill-wave" title="No payments yet" subtitle="This supplier has no payments recorded." />
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
