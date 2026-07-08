@php
    $roleBadges = [
        'owner' => 'badge-info',
        'admin' => 'badge-success',
        'manager' => 'badge-warning',
        'cashier' => 'badge-muted',
    ];
@endphp
<x-app-layout>
    <x-breadcrumb :items="['Users' => null]" />

    <x-page-header title="Users" subtitle="Manage system users and their access roles.">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add User
            </a>
        </x-slot>
    </x-page-header>

    <div class="card p-5">
        <form method="GET" action="{{ route('users.index') }}" class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-muted"></i>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or email..."
                    class="form-input pl-9"
                >
            </div>
            <select name="role" class="form-select w-full sm:w-48" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach (['owner', 'admin', 'manager', 'cashier'] as $role)
                    <option value="{{ $role }}" @selected(request('role') === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary">Search</button>
            @if (request('search') || request('role'))
                <a href="{{ route('users.index') }}" class="btn-secondary">Clear</a>
            @endif
        </form>

        @if ($users->isEmpty())
            <x-empty-state icon="fa-user-shield" title="No users yet" subtitle="Get started by adding your first user." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-modern">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="font-medium text-ink">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td><span class="{{ $roleBadges[$user->role] ?? 'badge-muted' }}">{{ ucfirst($user->role) }}</span></td>
                                <td>
                                    @if ($user->status)
                                        <span class="badge-success">Active</span>
                                    @else
                                        <span class="badge-muted">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('users.show', $user) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="rounded-lg p-2 text-muted hover:bg-surface-soft hover:text-primary-600" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <x-delete-form :action="route('users.destroy', $user)" label="{{ $user->name }}" />
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
