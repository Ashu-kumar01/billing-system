<x-app-layout>
    <x-breadcrumb :items="['Suppliers' => null]" />

    <x-page-header title="Suppliers" subtitle="Manage your supplier records and outstanding balances.">
        <x-slot name="actions">
            <a href="{{ route('suppliers.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Supplier
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('suppliers.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name, company or phone..."
                    class="form-input pl-9"
                >
            </div>
            <button type="submit" class="btn-secondary">Search</button>
            @if (request('search'))
                <a href="{{ route('suppliers.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        @if ($suppliers->isEmpty())
            <x-empty-state icon="fa-truck-field" title="No suppliers yet" subtitle="Get started by adding your first supplier." />
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
                        @foreach ($suppliers as $supplier)
                            <tr>
                                <td class="font-medium text-ink">{{ $supplier->name }}</td>
                                <td>{{ $supplier->email ?? '—' }}</td>
                                <td>{{ $supplier->phone ?? '—' }}</td>
                                <td>{{ $supplier->city ?? '—' }}</td>
                                <td>₹{{ number_format($supplier->outstandingBalance(), 2) }}</td>
                                <td>
                                    @if ($supplier->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('suppliers.destroy', $supplier)" label="{{ $supplier->name }}" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
