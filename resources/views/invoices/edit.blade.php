<x-app-layout>
    <x-breadcrumb :items="['Invoices' => route('invoices.index'), $invoice->invoice_no => null]" />

    <x-page-header title="Edit Invoice" subtitle="Update invoice header details." />

    <div class="card p-6 max-w-2xl">
        <form method="POST" action="{{ route('invoices.update', $invoice) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="customer_id" value="Customer" />
                <select id="customer_id" name="customer_id" class="form-select">
                    <option value="">Walk-in Customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id', $invoice->customer_id) == $customer->id)>{{ $customer->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="invoice_date" value="Invoice Date" />
                <x-text-input id="invoice_date" type="date" name="invoice_date" :value="old('invoice_date', $invoice->invoice_date->format('Y-m-d'))" required />
                <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="note" value="Note" />
                <textarea id="note" name="note" rows="3" class="form-textarea">{{ old('note', $invoice->note) }}</textarea>
            </div>

            <p class="text-xs text-muted">Line items cannot be changed after creation to keep stock records accurate. Delete and recreate the invoice if items need to change.</p>

            <div class="flex gap-2">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('invoices.show', $invoice) }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-app-layout>
