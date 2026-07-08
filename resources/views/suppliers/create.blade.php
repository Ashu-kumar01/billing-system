<x-app-layout>
    <x-breadcrumb :items="['Suppliers' => route('suppliers.index'), 'Add Supplier' => null]" />

    <x-page-header title="Add Supplier" subtitle="Create a new supplier record." />

    <div class="card p-6">
        <form method="POST" action="{{ route('suppliers.store') }}">
            @csrf
            @include('suppliers._form')
        </form>
    </div>
</x-app-layout>
