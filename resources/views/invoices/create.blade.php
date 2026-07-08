<x-app-layout>
    <x-breadcrumb :items="['Invoices' => route('invoices.index'), 'New Invoice' => null]" />

    <x-page-header title="New Invoice" subtitle="Create a new invoice for a customer." />

    <form
        method="POST"
        action="{{ route('invoices.store') }}"
        x-data="invoiceForm({
            products: {{ $products->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'sku' => $p->sku, 'price' => (float) $p->selling_price, 'stock' => $p->stock])->values() }},
        })"
    >
        @csrf

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div class="card p-5 lg:col-span-2">
                <h3 class="mb-4 text-base font-semibold text-ink">Line Items</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-semibold uppercase text-muted">
                                <th class="pb-2 pr-2">Product</th>
                                <th class="pb-2 px-2 w-24">Qty</th>
                                <th class="pb-2 px-2 w-32">Price</th>
                                <th class="pb-2 px-2 w-28">Discount</th>
                                <th class="pb-2 px-2 w-28">Tax</th>
                                <th class="pb-2 px-2 w-32 text-right">Subtotal</th>
                                <th class="pb-2 pl-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr class="border-t border-border">
                                    <td class="py-2 pr-2">
                                        <select class="form-select" x-model.number="item.product_id" @change="fillPrice(index)">
                                            <option value="">Select product</option>
                                            <template x-for="p in products" :key="p.id">
                                                <option :value="p.id" x-text="p.name + ' (' + p.sku + ') - Stock: ' + p.stock"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="py-2 px-2"><input type="number" step="0.01" min="0.01" class="form-input" x-model.number="item.quantity"></td>
                                    <td class="py-2 px-2"><input type="number" step="0.01" min="0" class="form-input" x-model.number="item.unit_price"></td>
                                    <td class="py-2 px-2"><input type="number" step="0.01" min="0" class="form-input" x-model.number="item.discount"></td>
                                    <td class="py-2 px-2"><input type="number" step="0.01" min="0" class="form-input" x-model.number="item.tax"></td>
                                    <td class="py-2 px-2 text-right font-medium" x-text="'₹' + lineTotal(item).toFixed(2)"></td>
                                    <td class="py-2 pl-2 text-center">
                                        <button type="button" @click="removeItem(index)" class="text-muted hover:text-danger"><i class="fa-solid fa-xmark"></i></button>
                                    </td>

                                    <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                                    <input type="hidden" :name="'items['+index+'][quantity]'" :value="item.quantity">
                                    <input type="hidden" :name="'items['+index+'][unit_price]'" :value="item.unit_price">
                                    <input type="hidden" :name="'items['+index+'][discount]'" :value="item.discount">
                                    <input type="hidden" :name="'items['+index+'][tax]'" :value="item.tax">
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button" @click="addItem" class="btn-secondary btn-sm mt-4">
                    <i class="fa-solid fa-plus"></i> Add Row
                </button>
            </div>

            <div class="space-y-5">
                <div class="card p-5">
                    <h3 class="mb-4 text-base font-semibold text-ink">Invoice Details</h3>

                    <div class="space-y-4">
                        <div>
                            <x-input-label for="customer_id" value="Customer" />
                            <select id="customer_id" name="customer_id" class="form-select">
                                <option value="">Walk-in Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="invoice_date" value="Invoice Date" />
                            <x-text-input id="invoice_date" type="date" name="invoice_date" :value="old('invoice_date', now()->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('invoice_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="discount" value="Overall Discount" />
                            <x-text-input id="discount" type="number" step="0.01" min="0" name="discount" x-model.number="orderDiscount" />
                        </div>

                        <div>
                            <x-input-label for="paid_amount" value="Paid Amount" />
                            <x-text-input id="paid_amount" type="number" step="0.01" min="0" name="paid_amount" x-model.number="paidAmount" />
                        </div>

                        <div>
                            <x-input-label for="note" value="Note" />
                            <textarea id="note" name="note" rows="2" class="form-textarea">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="mb-4 text-base font-semibold text-ink">Summary</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-muted">Subtotal</dt><dd class="font-medium" x-text="'₹' + subtotal.toFixed(2)"></dd></div>
                        <div class="flex justify-between"><dt class="text-muted">Discount</dt><dd class="font-medium" x-text="'₹' + totalDiscount.toFixed(2)"></dd></div>
                        <div class="flex justify-between"><dt class="text-muted">Tax</dt><dd class="font-medium" x-text="'₹' + totalTax.toFixed(2)"></dd></div>
                        <div class="flex justify-between border-t border-border pt-2 text-base"><dt class="font-semibold text-ink">Grand Total</dt><dd class="font-bold text-primary-600" x-text="'₹' + grandTotal.toFixed(2)"></dd></div>
                    </dl>

                    <button type="submit" class="btn-primary w-full mt-5"><i class="fa-solid fa-check"></i> Save Invoice</button>
                    <a href="{{ route('invoices.index') }}" class="btn-secondary w-full mt-2 justify-center">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function invoiceForm({ products }) {
                return {
                    products,
                    items: [{ product_id: '', quantity: 1, unit_price: 0, discount: 0, tax: 0 }],
                    orderDiscount: 0,
                    paidAmount: 0,
                    addItem() {
                        this.items.push({ product_id: '', quantity: 1, unit_price: 0, discount: 0, tax: 0 });
                    },
                    removeItem(index) {
                        if (this.items.length > 1) this.items.splice(index, 1);
                    },
                    fillPrice(index) {
                        const product = this.products.find(p => p.id === this.items[index].product_id);
                        if (product) this.items[index].unit_price = product.price;
                    },
                    lineTotal(item) {
                        return (item.quantity * item.unit_price) - (item.discount || 0) + (item.tax || 0);
                    },
                    get subtotal() {
                        return this.items.reduce((sum, i) => sum + (i.quantity * i.unit_price), 0);
                    },
                    get totalTax() {
                        return this.items.reduce((sum, i) => sum + Number(i.tax || 0), 0);
                    },
                    get totalDiscount() {
                        return this.items.reduce((sum, i) => sum + Number(i.discount || 0), 0) + Number(this.orderDiscount || 0);
                    },
                    get grandTotal() {
                        return this.subtotal - this.totalDiscount + this.totalTax;
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
