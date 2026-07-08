<x-app-layout>
    <x-breadcrumb :items="['Reports' => route('reports.index'), 'Customers Report' => null]" />

    <x-page-header title="Customers Report" subtitle="Invoiced totals, payments and outstanding balances per customer." />

    <div class="card p-5">
        <form method="GET" action="{{ route('reports.customers') }}" class="mb-5 flex items-center gap-3">
            <label class="form-label !mb-0">Sort by outstanding</label>
            <select name="sort" class="form-select w-auto" onchange="this.form.submit()">
                <option value="due_desc" @selected($sort === 'due_desc')>Highest first</option>
                <option value="due_asc" @selected($sort === 'due_asc')>Lowest first</option>
            </select>
        </form>

        @if ($customers->isEmpty())
            <x-empty-state icon="fa-users" title="No customers yet" subtitle="No customer data available." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Total Invoiced</th>
                            <th>Total Paid</th>
                            <th>Outstanding Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td class="font-medium text-ink">{{ $customer->name }}</td>
                                <td>₹{{ number_format($customer->total_invoiced, 2) }}</td>
                                <td>₹{{ number_format($customer->total_paid, 2) }}</td>
                                <td class="{{ $customer->total_due > 0 ? 'text-danger font-medium' : '' }}">₹{{ number_format($customer->total_due, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
