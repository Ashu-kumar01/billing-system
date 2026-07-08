<x-app-layout>
    <x-breadcrumb :items="['Purchases' => route('purchases.index'), 'PO ' . $purchase->invoice_no => null]" />

    <x-page-header title="Purchase Order {{ $purchase->invoice_no }}" subtitle="Placed on {{ $purchase->purchase_date->format('d M Y') }}">
        <x-slot name="actions">
            <a href="{{ route('purchases.pdf', $purchase) }}" class="btn-secondary">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
            <a href="{{ route('payments.create', ['purchase_id' => $purchase->id]) }}" class="btn-secondary">
                <i class="fa-solid fa-money-bill-wave"></i> Record Payment
            </a>
            <a href="{{ route('purchases.edit', $purchase) }}" class="btn-primary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Supplier</p>
            <p class="mt-1 text-base font-semibold text-ink">{{ $purchase->supplier->name ?? '—' }}</p>
            @if ($purchase->supplier?->company_name)
                <p class="text-sm text-muted">{{ $purchase->supplier->company_name }}</p>
            @endif
        </div>
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Store</p>
            <p class="mt-1 text-base font-semibold text-ink">{{ $purchase->store->name ?? '—' }}</p>
            <p class="text-sm text-muted">Created by {{ $purchase->user->name ?? '—' }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-muted">Status</p>
            <div class="mt-1">
                @if ($purchase->status === 'completed')
                    <span class="badge-success">Completed</span>
                @elseif ($purchase->status === 'pending')
                    <span class="badge-warning">Pending</span>
                @else
                    <span class="badge-danger">Cancelled</span>
                @endif
            </div>
        </div>
    </div>

    <div class="card mb-6 p-6">
        <h3 class="mb-4 text-base font-semibold text-ink">Line Items</h3>
        <div class="overflow-x-auto">
            <table class="w-full table-modern">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Unit Cost</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Tax</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $item)
                        <tr>
                            <td class="font-medium text-ink">
                                {{ $item->product->name ?? 'Deleted product' }}
                                @if ($item->product?->sku)
                                    <span class="text-xs text-muted">({{ $item->product->sku }})</span>
                                @endif
                            </td>
                            <td class="text-right">{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                            <td class="text-right">₹{{ number_format($item->unit_cost, 2) }}</td>
                            <td class="text-right">₹{{ number_format($item->discount, 2) }}</td>
                            <td class="text-right">₹{{ number_format($item->tax, 2) }}</td>
                            <td class="text-right font-medium text-ink">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <div class="w-full max-w-sm space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-muted">Subtotal</span><span class="font-medium text-ink">₹{{ number_format($purchase->subtotal, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-muted">Discount</span><span class="font-medium text-ink">- ₹{{ number_format($purchase->discount, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-muted">Tax</span><span class="font-medium text-ink">+ ₹{{ number_format($purchase->tax, 2) }}</span></div>
                <div class="flex justify-between border-t border-border pt-2 text-base"><span class="font-semibold text-ink">Total</span><span class="font-bold text-ink">₹{{ number_format($purchase->total, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-muted">Paid</span><span class="font-medium text-success">₹{{ number_format($purchase->paid_amount, 2) }}</span></div>
                <div class="flex justify-between"><span class="text-muted">Due</span><span class="font-medium text-danger">₹{{ number_format($purchase->due_amount, 2) }}</span></div>
            </div>
        </div>
    </div>

    @if ($purchase->note)
        <div class="card mb-6 p-6">
            <h3 class="mb-2 text-base font-semibold text-ink">Note</h3>
            <p class="text-sm text-muted">{{ $purchase->note }}</p>
        </div>
    @endif

    <div class="card p-6">
        <h3 class="mb-4 text-base font-semibold text-ink">Payments</h3>
        @if ($purchase->payments->isEmpty())
            <x-empty-state icon="fa-money-bill-wave" title="No payments recorded" subtitle="Payments made against this purchase order will appear here." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Transaction ID</th>
                            <th class="text-right">Amount</th>
                            <th>Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->payments as $payment)
                            <tr>
                                <td>{{ optional($payment->payment_date)->format('d M Y') }}</td>
                                <td class="capitalize">{{ str_replace('_', ' ', $payment->payment_method) }}</td>
                                <td>{{ $payment->transaction_id ?? '—' }}</td>
                                <td class="text-right">₹{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ $payment->note ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
