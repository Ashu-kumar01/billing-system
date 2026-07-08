<x-app-layout>
    <x-page-header title="Units" subtitle="Manage your product units of measurement.">
        <x-slot name="actions">
            <a href="{{ route('units.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> New Unit
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('units.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1 max-w-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-sm text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search units..."
                    class="form-input pl-9"
                >
            </div>
            <button type="submit" class="btn-secondary">
                <i class="fa-solid fa-filter"></i> Search
            </button>
            @if (request('search'))
                <a href="{{ route('units.index') }}" class="btn-secondary">
                    <i class="fa-solid fa-xmark"></i> Clear
                </a>
            @endif
        </form>

        @if ($units->isEmpty())
            <x-empty-state
                icon="fa-ruler"
                title="No units found"
                subtitle="Get started by creating a new unit."
            >
                <a href="{{ route('units.create') }}" class="btn-primary mt-2">
                    <i class="fa-solid fa-plus"></i> New Unit
                </a>
            </x-empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Short Name</th>
                            <th>Description</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $unit)
                            <tr>
                                <td class="font-medium">{{ $unit->name }}</td>
                                <td class="text-muted">{{ $unit->short_name }}</td>
                                <td class="text-muted">{{ Str::limit($unit->description, 60) ?: '—' }}</td>
                                <td>{{ $unit->products_count }}</td>
                                <td>
                                    @if ($unit->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('units.edit', $unit) }}" class="rounded-lg p-2 text-muted hover:bg-primary-50 hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <x-delete-form :action="route('units.destroy', $unit)" :label="$unit->name" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $units->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
