@php
    $product = $product ?? null;
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <x-input-label for="name" value="Product Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $product?->name) }}" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sku" value="SKU" />
        <x-text-input id="sku" name="sku" type="text" class="mt-1 block w-full" value="{{ old('sku', $product?->sku) }}" required />
        <x-input-error :messages="$errors->get('sku')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="barcode" value="Barcode" />
        <x-text-input id="barcode" name="barcode" type="text" class="mt-1 block w-full" value="{{ old('barcode', $product?->barcode) }}" />
        <x-input-error :messages="$errors->get('barcode')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" value="Category" />
        <select id="category_id" name="category_id" class="form-select mt-1 block w-full" required>
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="unit_id" value="Unit" />
        <select id="unit_id" name="unit_id" class="form-select mt-1 block w-full" required>
            <option value="">Select unit</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $product?->unit_id) == $unit->id)>
                    {{ $unit->name }} ({{ $unit->short_name }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="supplier_id" value="Supplier (optional)" />
        <select id="supplier_id" name="supplier_id" class="form-select mt-1 block w-full">
            <option value="">Select supplier</option>
            @foreach ($suppliers as $supplier)
                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $product?->supplier_id) == $supplier->id)>
                    {{ $supplier->name }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="cost_price" value="Cost Price" />
        <x-text-input id="cost_price" name="cost_price" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('cost_price', $product?->cost_price) }}" required />
        <x-input-error :messages="$errors->get('cost_price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="selling_price" value="Selling Price" />
        <x-text-input id="selling_price" name="selling_price" type="number" step="0.01" min="0" class="mt-1 block w-full" value="{{ old('selling_price', $product?->selling_price) }}" required />
        <x-input-error :messages="$errors->get('selling_price')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="stock" value="Stock Quantity" />
        <x-text-input id="stock" name="stock" type="number" min="0" class="mt-1 block w-full" value="{{ old('stock', $product?->stock ?? 0) }}" required />
        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="alert_quantity" value="Alert Quantity" />
        <x-text-input id="alert_quantity" name="alert_quantity" type="number" min="0" class="mt-1 block w-full" value="{{ old('alert_quantity', $product?->alert_quantity ?? 5) }}" required />
        <x-input-error :messages="$errors->get('alert_quantity')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="3" class="form-textarea mt-1 block w-full">{{ old('description', $product?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" value="Status" />
        <select id="status" name="status" class="form-select mt-1 block w-full">
            <option value="1" @selected(old('status', $product?->status ?? true) == 1)>Active</option>
            <option value="0" @selected(old('status', $product?->status ?? true) == 0)>Inactive</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>
