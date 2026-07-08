<x-app-layout>
    <x-breadcrumb :items="['Suppliers' => route('suppliers.index'), 'Edit Supplier' => null]" />

    <x-page-header title="Edit Supplier" subtitle="Update details for {{ $supplier->name }}." />

    <div class="card p-6">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')
            @include('suppliers._form')
        </form>
    </div>
</x-app-layout>
