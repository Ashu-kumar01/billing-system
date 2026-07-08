<x-app-layout>
    <x-breadcrumb :items="['Invoices' => route('invoices.index'), $invoice->invoice_no => null]" />

    <x-page-header title="Invoice {{ $invoice->invoice_no }}" subtitle="Created on {{ $invoice->invoice_date->format('d M Y') }}">
        <x-slot name="actions">
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn-secondary"><i class="fa-regular fa-file-pdf"></i> Download PDF</a>
            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn-secondary"><i class="fa-solid fa-print"></i> Print</a>
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn-primary"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-2">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted">Billed To</p>
                    <p class="text-base font-semibold text-ink">{{ $invoice->customer->name ?? 'Walk-in Customer' }}</p>
                    @if ($invoice->customer)
                        <p class="text-sm text-muted">{{ $invoice->customer->email }} · {{ $invoice->customer->phone }}</p>
                    @endif
                </div>
                <x-badge :color="['paid' => 'success', 'partial' => 'warning', 'due' => 'danger'][$invoice->payment_status]" class="text-sm">
                    {{ ucfirst($invoice->payment_status) }}
                </x-badge>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Tax</th>
                            <th class="text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $item)
                            <tr>
                                <td>{{ $item->product->name ?? '—' }}</td>
                                <td>{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.') }}</td>
                                <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                <td>₹{{ number_format($item->discount, 2) }}</td>
                                <td>₹{{ number_format($item->tax, 2) }}</td>
                                <td class="text-right font-medium">₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($invoice->note)
                <div class="mt-5 rounded-xl bg-surface-muted p-4 text-sm text-muted">{{ $invoice->note }}</div>
            @endif
        </div>

        <div class="space-y-5">
            <div class="card p-5">
                <h3 class="mb-4 text-base font-semibold text-ink">Totals</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd class="font-medium">₹{{ number_format($invoice->subtotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Discount</dt><dd class="font-medium">₹{{ number_format($invoice->discount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Tax</dt><dd class="font-medium">₹{{ number_format($invoice->tax, 2) }}</dd></div>
                    <div class="flex justify-between border-t border-border pt-2 text-base"><dt class="font-semibold text-ink">Total</dt><dd class="font-bold text-primary-600">₹{{ number_format($invoice->total, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Paid</dt><dd class="font-medium text-success">₹{{ number_format($invoice->paid_amount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-muted">Due</dt><dd class="font-medium text-danger">₹{{ number_format($invoice->due_amount, 2) }}</dd></div>
                </dl>

                @if ($invoice->due_amount > 0)
                    <a href="{{ route('payments.create', ['invoice_id' => $invoice->id]) }}" class="btn-primary w-full mt-5 justify-center">
                        <i class="fa-solid fa-money-bill"></i> Receive Payment
                    </a>
                @endif
            </div>

            <div class="card p-5">
                <h3 class="mb-3 text-base font-semibold text-ink">Payment History</h3>
                <div class="space-y-3">
                    @forelse ($invoice->payments as $payment)
                        <div class="flex items-center justify-between rounded-xl bg-surface-muted px-3 py-2.5 text-sm">
                            <div>
                                <p class="font-medium text-ink">₹{{ number_format($payment->amount, 2) }}</p>
                                <p class="text-xs text-muted">{{ $payment->payment_date->format('d M Y') }} · {{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-muted">No payments recorded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
