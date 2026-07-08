<x-app-layout>
    <x-breadcrumb :items="['Products' => route('products.index'), 'Edit Product' => null]" />

    <x-page-header title="Edit Product" subtitle="Update details for {{ $product->name }}." />

    <div class="card p-6">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')

            @include('products._form')

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Update Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
