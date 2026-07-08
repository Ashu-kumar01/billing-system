<x-app-layout>
    <x-breadcrumb :items="['Purchases' => route('purchases.index'), 'New Purchase' => null]" />

    <x-page-header title="New Purchase Order" subtitle="Record stock received from a supplier." />

    <form method="POST" action="{{ route('purchases.store') }}" x-data="purchaseForm()" x-init="init()">
        @csrf

        <div class="card mb-6 p-6">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="form-label">Supplier</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">Select supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                {{ $supplier->name }}{{ $supplier->company_name ? ' — ' . $supplier->company_name : '' }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('supplier_id')" class="mt-1" />
                </div>

                <div>
                    <label class="form-label">Purchase Date</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" class="form-input" required>
                    <x-input-error :messages="$errors->get('purchase_date')" class="mt-1" />
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="completed" @selected(old('status', 'completed') === 'completed')>Completed</option>
                        <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                        <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>

                <div class="sm:col-span-3">
                    <label class="form-label">Note</label>
                    <textarea name="note" class="form-textarea" rows="2" placeholder="Optional note...">{{ old('note') }}</textarea>
                    <x-input-error :messages="$errors->get('note')" class="mt-1" />
                </div>
            </div>
        </div>

        <div class="card mb-6 p-6">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-ink">Line Items</h3>
                <button type="button" class="btn-secondary btn-sm" @click="addRow()">
                    <i class="fa-solid fa-plus"></i> Add Row
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">Product</th>
                            <th class="text-right" style="width: 110px;">Quantity</th>
                            <th class="text-right" style="width: 130px;">Unit Cost</th>
                            <th class="text-right" style="width: 110px;">Discount</th>
                            <th class="text-right" style="width: 110px;">Tax</th>
                            <th class="text-right" style="width: 130px;">Subtotal</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(row, index) in items" :key="row.uid">
                            <tr>
                                <td>
                                    <select
                                        class="form-select"
                                        :name="`items[${index}][product_id]`"
                                        x-model.number="row.product_id"
                                        @change="onProductChange(row)"
                                        required
                                    >
                                        <option value="">Select product</option>
                                        <template x-for="product in products" :key="product.id">
                                            <option :value="product.id" x-text="`${product.name} (${product.sku}) — stock: ${product.stock}`"></option>
                                        </template>
                                    </select>
                                </td>
                                <td>
                                    <input
                                        type="number" step="0.01" min="0.01"
                                        class="form-input text-right"
                                        :name="`items[${index}][quantity]`"
                                        x-model.number="row.quantity"
                                        @input="recalcRow(row)"
                                        required
                                    >
                                </td>
                                <td>
                                    <input
                                        type="number" step="0.01" min="0"
                                        class="form-input text-right"
                                        :name="`items[${index}][unit_cost]`"
                                        x-model.number="row.unit_cost"
                                        @input="recalcRow(row)"
                                        required
                                    >
                                </td>
                                <td>
                                    <input
                                        type="number" step="0.01" min="0"
                                        class="form-input text-right"
                                        :name="`items[${index}][discount]`"
                                        x-model.number="row.discount"
                                        @input="recalcRow(row)"
                                    >
                                </td>
                                <td>
                                    <input
                                        type="number" step="0.01" min="0"
                                        class="form-input text-right"
                                        :name="`items[${index}][tax]`"
                                        x-model.number="row.tax"
                                        @input="recalcRow(row)"
                                    >
                                </td>
                                <td class="text-right font-medium text-ink" x-text="`₹${row.subtotal.toFixed(2)}`"></td>
                                <td class="text-center">
                                    <button type="button" class="rounded-lg p-2 text-muted hover:bg-red-50 hover:text-danger" @click="removeRow(index)" x-show="items.length > 1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <template x-if="items.length === 0">
                <p class="mt-4 text-sm text-muted">No items added yet. Click "Add Row" to begin.</p>
            </template>

            <div class="mt-6 flex justify-end">
                <div class="w-full max-w-sm space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-muted">Subtotal</span><span class="font-medium text-ink" x-text="`₹${totals.subtotal.toFixed(2)}`"></span></div>
                    <div class="flex justify-between"><span class="text-muted">Discount</span><span class="font-medium text-ink" x-text="`- ₹${totals.discount.toFixed(2)}`"></span></div>
                    <div class="flex justify-between"><span class="text-muted">Tax</span><span class="font-medium text-ink" x-text="`+ ₹${totals.tax.toFixed(2)}`"></span></div>
                    <div class="flex justify-between border-t border-border pt-2 text-base"><span class="font-semibold text-ink">Total</span><span class="font-bold text-ink" x-text="`₹${totals.total.toFixed(2)}`"></span></div>
                </div>
            </div>
        </div>

        <div class="card mb-6 p-6">
            <h3 class="mb-4 text-base font-semibold text-ink">Payment</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-select">
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="card">Card</option>
                        <option value="cheque">Cheque</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Paid Amount</label>
                    <input type="number" step="0.01" min="0" name="paid_amount" value="{{ old('paid_amount', 0) }}" class="form-input">
                    <x-input-error :messages="$errors->get('paid_amount')" class="mt-1" />
                </div>
                <div class="flex items-end">
                    <p class="text-sm text-muted">Due amount will be calculated automatically as Total − Paid.</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('purchases.index') }}" class="btn-secondary">Cancel</a>
            <button type="submit" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Purchase Order
            </button>
        </div>
    </form>

    @php
        $productsForJs = $products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'cost_price' => (float) $p->cost_price,
                'selling_price' => (float) $p->selling_price,
                'stock' => $p->stock,
            ];
        })->values();
    @endphp

    <script>
        function purchaseForm() {
            return {
                products: {!! $productsForJs->toJson() !!},
                items: [],
                totals: { subtotal: 0, discount: 0, tax: 0, total: 0 },
                nextUid: 1,

                init() {
                    this.addRow();
                },

                addRow() {
                    this.items.push({
                        uid: this.nextUid++,
                        product_id: '',
                        quantity: 1,
                        unit_cost: 0,
                        discount: 0,
                        tax: 0,
                        subtotal: 0,
                    });
                    this.recalcTotals();
                },

                removeRow(index) {
                    if (this.items.length <= 1) return;
                    this.items.splice(index, 1);
                    this.recalcTotals();
                },

                onProductChange(row) {
                    const product = this.products.find(p => p.id === row.product_id);
                    if (product) {
                        row.unit_cost = product.cost_price;
                    }
                    this.recalcRow(row);
                },

                recalcRow(row) {
                    const qty = parseFloat(row.quantity) || 0;
                    const cost = parseFloat(row.unit_cost) || 0;
                    const discount = parseFloat(row.discount) || 0;
                    const tax = parseFloat(row.tax) || 0;
                    row.subtotal = (qty * cost) - discount + tax;
                    this.recalcTotals();
                },

                recalcTotals() {
                    let subtotal = 0, discount = 0, tax = 0;
                    this.items.forEach(row => {
                        const qty = parseFloat(row.quantity) || 0;
                        const cost = parseFloat(row.unit_cost) || 0;
                        subtotal += qty * cost;
                        discount += parseFloat(row.discount) || 0;
                        tax += parseFloat(row.tax) || 0;
                    });
                    this.totals.subtotal = subtotal;
                    this.totals.discount = discount;
                    this.totals.tax = tax;
                    this.totals.total = subtotal - discount + tax;
                },
            };
        }
    </script>
</x-app-layout>
