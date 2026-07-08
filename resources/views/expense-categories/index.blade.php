<x-app-layout>
    @php($breadcrumbs = ['Expense Categories' => null])
    <x-page-header title="Expense Categories" subtitle="Manage lookup categories for expenses.">
        <x-slot name="actions">
            <a href="{{ route('expense-categories.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> New Category
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('expense-categories.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1 max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search categories..."
                    class="form-input pl-9"
                >
            </div>
            <button type="submit" class="btn-secondary">
                <i class="fa-solid fa-filter"></i> Search
            </button>
            @if (request('search'))
                <a href="{{ route('expense-categories.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Clear
                </a>
            @endif
        </form>

        @if ($expenseCategories->isEmpty())
            <x-empty-state
                icon="fa-tags"
                title="No expense categories found"
                subtitle="Get started by creating a new category."
            >
                <a href="{{ route('expense-categories.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus"></i> New Category
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenseCategories as $category)
                            <tr>
                                <td class="font-medium">{{ $category->name }}</td>
                                <td class="text-muted">{{ $category->slug }}</td>
                                <td class="text-muted">{{ Str::limit($category->description, 60) ?: '—' }}</td>
                                <td>
                                    @if ($category->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('expense-categories.edit', $category) }}" class="rounded-lg p-2 text-muted hover:bg-primary-50 hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('expense-categories.destroy', $category)" :label="$category->name" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $expenseCategories->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
