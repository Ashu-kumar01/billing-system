<x-app-layout>
    <x-breadcrumb :items="['Customers' => route('customers.index'), 'Add Customer' => null]" />

    <x-page-header title="Add Customer" subtitle="Create a new customer record." />

    <div class="card p-6">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf
            @include('customers._form')
        </form>
    </div>
</x-app-layout>
