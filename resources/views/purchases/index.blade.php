<x-app-layout>
    <x-breadcrumb :items="['Purchases' => null]" />

    <x-page-header title="Purchases" subtitle="Manage supplier purchase orders and stock receipts.">
        <x-slot name="actions">
            <a href="{{ route('purchases.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> New Purchase
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="card p-5">
        <form method="GET" action="{{ route('purchases.index') }}" class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-5">
            <div class="relative sm:col-span-2">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by PO # or supplier..."
                    class="form-input pl-9"
                >
            </div>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>
            <input type="date" name="from" value="{{ request('from') }}" class="form-input" title="From date">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input" title="To date">

            <div class="flex items-center gap-2 sm:col-span-5">
                <button type="submit" class="btn-secondary">Filter</button>
                @if (request()->hasAny(['search', 'status', 'from', 'to']))
                    <a href="{{ route('purchases.index') }}" class="btn-secondary">Clear</a>
                @endif
            </div>
        </form>

        @if ($purchases->isEmpty())
            <x-empty-state icon="fa-cart-shopping" title="No purchases yet" subtitle="Get started by creating your first purchase order.">
                <a href="{{ route('purchases.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus"></i> New Purchase
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>PO #</th>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th class="text-right">Total</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Due</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td class="font-medium text-ink">{{ $purchase->invoice_no }}</td>
                                <td>{{ $purchase->supplier->name ?? '—' }}</td>
                                <td>{{ $purchase->purchase_date->format('d M Y') }}</td>
                                <td class="text-right">₹{{ number_format($purchase->total, 2) }}</td>
                                <td class="text-right">₹{{ number_format($purchase->paid_amount, 2) }}</td>
                                <td class="text-right">₹{{ number_format($purchase->due_amount, 2) }}</td>
                                <td>
                                    @if ($purchase->status === 'completed')
                                        <span class="badge-success">Completed</span>
                                    @elseif ($purchase->status === 'pending')
                                        <span class="badge-warning">Pending</span>
                                    @else
                                        <span class="badge-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('purchases.edit', $purchase) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('purchases.destroy', $purchase)" label="PO {{ $purchase->invoice_no }}" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
