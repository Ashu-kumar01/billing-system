<x-app-layout>
    <x-breadcrumb :items="['Products' => route('products.index'), $product->name => null]" />

    <x-page-header title="{{ $product->name }}" subtitle="SKU: {{ $product->sku }}">
        <x-slot name="actions">
            <a href="{{ route('products.edit', $product) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="{{ route('products.index') }}" class="btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card p-6 lg:col-span-1">
            <h3 class="mb-4 text-sm font-semibold text-ink">Product Info</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Category</dt>
                    <dd class="font-medium text-ink">{{ $product->category?->name ?? '-' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Unit</dt>
                    <dd class="font-medium text-ink">{{ $product->unit?->name ?? '-' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Supplier</dt>
                    <dd class="font-medium text-ink">{{ $product->supplier?->name ?? '-' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Barcode</dt>
                    <dd class="font-medium text-ink">{{ $product->barcode ?? '-' }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Cost Price</dt>
                    <dd class="font-medium text-ink">₹{{ number_format($product->cost_price, 2) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Selling Price</dt>
                    <dd class="font-medium text-ink">₹{{ number_format($product->selling_price, 2) }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Stock</dt>
                    <dd class="font-medium">
                        @if ($product->isLowStock())
                            <span class="badge-danger">{{ $product->stock }} low</span>
                        @else
                            <span class="text-ink">{{ $product->stock }}</span>
                        @endif
                    </dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Alert Quantity</dt>
                    <dd class="font-medium text-ink">{{ $product->alert_quantity }}</dd>
                </div>
                <div class="flex items-center justify-between">
                    <dt class="text-muted">Status</dt>
                    <dd>
                        @if ($product->status)
                            <span class="badge-success">Active</span>
                        @else
                            <span class="badge-muted">Inactive</span>
                        @endif
                    </dd>
                </div>
            </dl>

            @if ($product->description)
                <div class="mt-4 border-t border-border pt-4">
                    <p class="text-xs font-semibold uppercase text-muted">Description</p>
                    <p class="mt-1 text-sm text-ink">{{ $product->description }}</p>
                </div>
            @endif
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="card p-0">
                <div class="border-b border-border p-4">
                    <h3 class="text-sm font-semibold text-ink">Recent Stock Movements</h3>
                </div>
                @if ($stockMovements->isEmpty())
                    <x-empty-state icon="fa-clock-rotate-left" title="No stock movements" subtitle="Stock changes for this product will appear here." />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full table-modern">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>Stock Before</th>
                                    <th>Stock After</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockMovements as $movement)
                                    <tr>
                                        <td>{{ $movement->created_at?->format('d M Y H:i') }}</td>
                                        <td>{{ ucfirst($movement->type) }}</td>
                                        <td>{{ $movement->quantity_change ?? $movement->quantity }}</td>
                                        <td>{{ $movement->stock_before }}</td>
                                        <td>{{ $movement->stock_after }}</td>
                                        <td>{{ $movement->note ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="card p-0">
                <div class="border-b border-border p-4">
                    <h3 class="text-sm font-semibold text-ink">Recent Invoice Items</h3>
                </div>
                @if ($invoiceItems->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="No invoice history" subtitle="Sales of this product will appear here." />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full table-modern">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoiceItems as $item)
                                    <tr>
                                        <td>{{ $item->invoice?->invoice_no ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->unit_price, 2) }}</td>
                                        <td>₹{{ number_format($item->discount, 2) }}</td>
                                        <td>₹{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
