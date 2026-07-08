<x-app-layout>
    @php($breadcrumbs = ['Expenses' => null])
    <x-page-header title="Expenses" subtitle="Track and manage your business expenses.">
        <x-slot name="actions">
            <a href="{{ route('expenses.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Expense
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('expenses.index') }}" class="mb-5 flex flex-col gap-3 lg:flex-row lg:items-center lg:flex-wrap">
            <div class="relative flex-1 max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search title or reference..."
                    class="form-input pl-9"
                >
            </div>

            <select name="category" class="form-select w-auto">
                <option value="">All Categories</option>
                @foreach (\App\Models\Expense::CATEGORIES as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select>

            <input type="date" name="from" value="{{ request('from') }}" class="form-input w-auto" title="From date">
            <input type="date" name="to" value="{{ request('to') }}" class="form-input w-auto" title="To date">

            <button type="submit" class="btn-secondary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if (request()->anyFilled(['search', 'category', 'from', 'to']))
                <a href="{{ route('expenses.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Clear
                </a>
            @endif
        </form>

        @if ($expenses->isEmpty())
            <x-empty-state
                icon="fa-receipt"
                title="No expenses yet"
                subtitle="Get started by recording a new expense."
            >
                <a href="{{ route('expenses.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus"></i> Add Expense
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Reference</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenses as $expense)
                            <tr>
                                <td class="text-muted">{{ $expense->expense_date->format('d M Y') }}</td>
                                <td class="font-medium">{{ $expense->title }}</td>
                                <td><x-badge color="info">{{ ucfirst($expense->category) }}</x-badge></td>
                                <td class="font-semibold">₹{{ number_format($expense->amount, 2) }}</td>
                                <td class="text-muted">{{ $expense->reference_no ?: '—' }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('expenses.show', $expense) }}" class="rounded-lg p-2 text-muted hover:bg-primary-50 hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('expenses.edit', $expense) }}" class="rounded-lg p-2 text-muted hover:bg-primary-50 hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('expenses.destroy', $expense)" :label="$expense->title" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $expenses->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
