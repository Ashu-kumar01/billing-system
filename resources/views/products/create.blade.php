<x-app-layout>
    <x-breadcrumb :items="['Products' => route('products.index'), 'Add Product' => null]" />

    <x-page-header title="Add Product" subtitle="Create a new product in your catalog." />

    <div class="card p-6">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf

            @include('products._form')

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('products.index') }}" class="btn-secondary">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Save Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
