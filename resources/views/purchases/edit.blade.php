<x-app-layout>
    <x-breadcrumb :items="['Purchases' => route('purchases.index'), 'PO ' . $purchase->invoice_no => route('purchases.show', $purchase), 'Edit' => null]" />

    <x-page-header title="Edit Purchase Order" subtitle="Update header details for {{ $purchase->invoice_no }}. Line items cannot be changed after creation." />

    <div class="card p-6">
        <form method="POST" action="{{ route('purchases.update', $purchase) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchase->supplier_id) == $supplier->id)>
                                {{ $supplier->name }}{{ $supplier->company_name ? ' — ' . $supplier->company_name : '' }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-1" />
                </div>

                <div>
                    <label class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date->format('Y-m-d')) }}" class="form-input" required>
                    <x-input-error :messages="$errors->get('purchase_date')" class="mt-1" />
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="completed" @selected(old('status', $purchase->status) === 'completed')>Completed</option>
                        <option value="pending" @selected(old('status', $purchase->status) === 'pending')>Pending</option>
                        <option value="cancelled" @selected(old('status', $purchase->status) === 'cancelled')>Cancelled</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <div class="sm:col-span-2">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-textarea" rows="3">{{ old('note', $purchase->note) }}</textarea>
                    <x-input-error :messages="$errors->get('note')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('purchases.show', $purchase) }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
