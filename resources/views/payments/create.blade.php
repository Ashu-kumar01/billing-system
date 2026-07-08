<x-app-layout>
    @php($breadcrumbs = ['Payments' => route('payments.index'), 'Record Payment' => null])
    <x-page-header title="Record Payment" subtitle="Record a receipt from a customer or a payout to a supplier." />

    <div class="card p-6 max-w-3xl" x-data="{ mode: 'invoice' }">
        <form method="POST" action="{{ route('payments.store') }}" class="space-y-6">
            @csrf

            <div class="flex gap-2 rounded-xl bg-surface-soft p-1 w-fit">
                <button type="button" @click="mode = 'invoice'" class="rounded-lg px-4 py-2 text-sm font-medium transition" :class="mode === 'invoice' ? 'bg-white shadow text-primary-600' : 'text-muted'">
                    <i class="fa-solid fa-file-invoice"></i> Receive from Customer
                </button>
                <button type="button" @click="mode = 'purchase'" class="rounded-lg px-4 py-2 text-sm font-medium transition" :class="mode === 'purchase' ? 'bg-white shadow text-primary-600' : 'text-muted'">
                    <i class="fa-solid fa-truck"></i> Pay to Supplier
                </button>
            </div>

            <div x-show="mode === 'invoice'">
                <x-input-label for="invoice_id" value="Invoice" />
                <select id="invoice_id" name="invoice_id" class="form-select" x-bind:disabled="mode !== 'invoice'">
                    <option value="">Select an invoice...</option>
                    @foreach ($invoices as $invoice)
                        <option value="{{ $invoice->id }}" @selected(old('invoice_id') == $invoice->id)>
                            {{ $invoice->invoice_no }} — {{ $invoice->customer->name ?? 'N/A' }} (Due: ₹{{ number_format($invoice->due_amount, 2) }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('invoice_id')" class="mt-2" />
            </div>

            <div x-show="mode === 'purchase'">
                <x-input-label for="purchase_id" value="Purchase" />
                <select id="purchase_id" name="purchase_id" class="form-select" x-bind:disabled="mode !== 'purchase'">
                    <option value="">Select a purchase...</option>
                    @foreach ($purchases as $purchase)
                        <option value="{{ $purchase->id }}" @selected(old('purchase_id') == $purchase->id)>
                            {{ $purchase->invoice_no }} — {{ $purchase->supplier->name ?? 'N/A' }} (Due: ₹{{ number_format($purchase->due_amount, 2) }})
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('purchase_id')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <x-input-label for="amount" value="Amount" />
                    <x-text-input id="amount" type="number" step="0.01" min="0.01" name="amount" :value="old('amount')" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="payment_date" value="Payment Date" />
                    <x-text-input id="payment_date" type="date" name="payment_date" :value="old('payment_date', now()->toDateString())" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('payment_date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="payment_method" value="Payment Method" />
                    <select id="payment_method" name="payment_method" class="form-select">
                        @foreach (['cash', 'card', 'upi', 'bank_transfer', 'cheque', 'other'] as $method)
                            <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ ucwords(str_replace('_', ' ', $method)) }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="transaction_id" value="Transaction ID (optional)" />
                    <x-text-input id="transaction_id" type="text" name="transaction_id" :value="old('transaction_id')" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('transaction_id')" class="mt-2" />
                </div>
            </div>

            <div>
                <x-input-label for="note" value="Note (optional)" />
                <textarea id="note" name="note" rows="3" class="form-textarea">{{ old('note') }}</textarea>
                <x-input-error :messages="$errors->get('note')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Payment
                </button>
                <a href="{{ route('payments.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
