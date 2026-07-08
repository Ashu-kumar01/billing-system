<x-app-layout>
    <x-breadcrumb :items="['Customers' => null]" />

    <x-page-header title="Customers" subtitle="Manage your customer records and outstanding balances.">
        <x-slot name="actions">
            <a href="{{ route('customers.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Customer
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('customers.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, email or phone..."
                    class="form-input pl-9"
                >
            </div>
            <button type="submit" class="btn-secondary">Search</button>
            @if (request('search'))
                <a href="{{ route('customers.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        @if ($customers->isEmpty())
            <x-empty-state icon="fa-users" title="No customers yet" subtitle="Get started by adding your first customer." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Outstanding Balance</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td class="font-medium text-ink">{{ $customer->name }}</td>
                                <td>{{ $customer->email ?? '—' }}</td>
                                <td>{{ $customer->phone ?? '—' }}</td>
                                <td>{{ $customer->city ?? '—' }}</td>
                                <td>₹{{ number_format($customer->outstandingBalance(), 2) }}</td>
                                <td>
                                    @if ($customer->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('customers.show', $customer) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('customers.destroy', $customer)" label="{{ $customer->name }}" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
