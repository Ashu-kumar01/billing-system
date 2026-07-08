<x-app-layout>
    <x-breadcrumb :items="['Invoices' => null]" />

    <x-page-header title="Invoices" subtitle="Manage customer invoices and billing.">
        <x-slot name="actions">
            <a href="{{ route('invoices.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> New Invoice
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5 mb-5">
        <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search invoice # or customer..." class="form-input sm:col-span-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                <option value="partial" @selected(request('status') === 'partial')>Partial</option>
                <option value="due" @selected(request('status') === 'due')>Due</option>
            </select>
            <button type="submit" class="btn-secondary justify-center"><i class="fa-solid fa-filter"></i> Filter</button>
        </form>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full table-modern">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr>
                            <td class="font-medium">{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->customer->name ?? '—' }}</td>
                            <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                            <td>₹{{ number_format($invoice->total, 2) }}</td>
                            <td>₹{{ number_format($invoice->paid_amount, 2) }}</td>
                            <td>₹{{ number_format($invoice->due_amount, 2) }}</td>
                            <td>
                                <x-badge :color="['paid' => 'success', 'partial' => 'warning', 'due' => 'danger'][$invoice->payment_status]">
                                    {{ ucfirst($invoice->payment_status) }}
                                </x-badge>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="View"><i class="fa-regular fa-eye"></i></a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <a href="{{ route('invoices.pdf', $invoice) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Download PDF"><i class="fa-regular fa-file-pdf"></i></a>
                                    <x-delete-form :action="route('invoices.destroy', $invoice)" :label="$invoice->invoice_no" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state icon="fa-file-invoice-dollar" title="No invoices yet" subtitle="Create your first invoice to start billing customers." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">{{ $invoices->links() }}</div>
</x-app-layout>
