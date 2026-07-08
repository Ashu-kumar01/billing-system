<x-app-layout>
    @php($breadcrumbs = ['Payments' => route('payments.index'), 'Payment #'.$payment->id => null])
    <x-page-header title="Payment Receipt" subtitle="Details of this payment transaction.">
        <x-slot name="actions">
            <a href="{{ route('payments.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="card max-w-2xl mx-auto overflow-hidden">
        <div class="bg-primary-600 px-6 py-8 text-center text-white" style="background-color:#2563EB">
            <i class="fa-solid fa-circle-check text-4xl"></i>
            <p class="mt-3 text-3xl font-bold">₹{{ number_format($payment->amount, 2) }}</p>
            <p class="mt-1 text-sm text-white/80">
                @if ($payment->invoice_id)
                    Received from Customer
                @else
                    Paid to Supplier
                @endif
            </p>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-border pb-3">
                <span class="text-sm text-muted">Payment Date</span>
                <span class="font-medium text-ink">{{ $payment->payment_date->format('d M Y') }}</span>
            </div>

            <div class="flex items-center justify-between border-b border-border pb-3">
                <span class="text-sm text-muted">Payment Method</span>
                @php($methodColors = ['cash' => 'success', 'card' => 'info', 'upi' => 'info', 'bank_transfer' => 'info', 'cheque' => 'warning', 'other' => 'muted'])
                <x-badge :color="$methodColors[$payment->payment_method] ?? 'muted'">
                    {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                </x-badge>
            </div>

            @if ($payment->transaction_id)
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <span class="text-sm text-muted">Transaction ID</span>
                    <span class="font-medium text-ink">{{ $payment->transaction_id }}</span>
                </div>
            @endif

            @if ($payment->invoice)
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <span class="text-sm text-muted">Invoice</span>
                    <span class="font-medium text-ink">{{ $payment->invoice->invoice_no }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <span class="text-sm text-muted">Customer</span>
                    <span class="font-medium text-ink">{{ $payment->customer->name ?? $payment->invoice->customer->name ?? '—' }}</span>
                </div>
            @endif

            @if ($payment->purchase)
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <span class="text-sm text-muted">Purchase</span>
                    <span class="font-medium text-ink">{{ $payment->purchase->invoice_no }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-border pb-3">
                    <span class="text-sm text-muted">Supplier</span>
                    <span class="font-medium text-ink">{{ $payment->supplier->name ?? $payment->purchase->supplier->name ?? '—' }}</span>
                </div>
            @endif

            <div class="flex items-center justify-between border-b border-border pb-3">
                <span class="text-sm text-muted">Recorded By</span>
                <span class="font-medium text-ink">{{ $payment->user->name ?? '—' }}</span>
            </div>

            @if ($payment->note)
                <div>
                    <span class="text-sm text-muted">Note</span>
                    <p class="mt-1 text-sm text-ink">{{ $payment->note }}</p>
                </div>
            @endif
        </div>

        <div class="border-t border-border px-6 py-4 flex justify-end">
            <x-delete-form :action="route('payments.destroy', $payment)" label="this payment" />
        </div>
    </div>
</x-app-layout>
