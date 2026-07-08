<x-app-layout>
    <x-breadcrumb :items="['Customers' => route('customers.index'), 'Edit Customer' => null]" />

    <x-page-header title="Edit Customer" subtitle="Update details for {{ $customer->name }}." />

    <div class="card p-6">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf
            @method('PUT')
            @include('customers._form')
        </form>
    </div>
</x-app-layout>
