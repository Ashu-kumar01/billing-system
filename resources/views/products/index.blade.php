<x-app-layout>
    <x-breadcrumb :items="['Products' => null]" />

    <x-page-header title="Products" subtitle="Manage your product catalog, stock and pricing.">
        <x-slot name="actions">
            <a href="{{ route('products.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card mb-6 p-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="flex-1">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, SKU or barcode..."
                    class="form-input w-full"
                >
            </div>

            <div class="sm:w-56">
                <select name="category_id" class="form-select w-full">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label class="flex items-center gap-2 whitespace-nowrap text-sm text-ink">
                <input type="checkbox" name="low_stock" value="1" class="rounded border-border" @checked(request('low_stock'))>
                Low stock only
            </label>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-magnifying-glass"></i> Filter
                </button>
                <a href="{{ route('products.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card p-0">
        @if ($products->isEmpty())
            <x-empty-state icon="fa-box" title="No products yet" subtitle="Add your first product to get started." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Cost Price</th>
                            <th>Selling Price</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td class="font-medium text-ink">{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>
                                    @if ($product->isLowStock())
                                        <span class="badge-danger">{{ $product->stock }} low</span>
                                    @else
                                        {{ $product->stock }}
                                    @endif
                                </td>
                                <td>₹{{ number_format($product->cost_price, 2) }}</td>
                                <td>₹{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if ($product->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('products.show', $product) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('products.destroy', $product)" label="{{ $product->name }}" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-border p-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
