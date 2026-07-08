@php
    $roleBadges = [
        'owner' => 'badge-info',
        'admin' => 'badge-success',
        'manager' => 'badge-warning',
        'cashier' => 'badge-muted',
    ];
@endphp
<x-app-layout>
    <x-breadcrumb :items="['Users' => route('users.index'), $user->name => null]" />

    <x-page-header title="{{ $user->name }}" subtitle="User profile and activity summary.">
        <x-slot name="actions">
            <a href="{{ route('users.edit', $user) }}" class="btn-secondary">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn-primary">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>
        </x-slot>
    </x-page-header>

    <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
        <x-stat-card label="Invoices Created" :value="$invoiceCount" icon="fa-file-invoice" color="primary" />
        <x-stat-card label="Purchases Recorded" :value="$purchaseCount" icon="fa-truck-ramp-box" color="secondary" />
        <x-stat-card label="Expenses Logged" :value="$expenseCount" icon="fa-receipt" color="danger" />
    </div>

    <div class="card p-6">
        <h2 class="mb-4 text-lg font-semibold text-ink">Account Information</h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs font-medium uppercase text-muted">Email</p>
                <p class="mt-1 text-sm text-ink">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Phone</p>
                <p class="mt-1 text-sm text-ink">{{ $user->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Role</p>
                <p class="mt-1"><span class="{{ $roleBadges[$user->role] ?? 'badge-muted' }}">{{ ucfirst($user->role) }}</span></p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Status</p>
                <p class="mt-1">
                    @if ($user->status)
                        <span class="badge-success">Active</span>
                    @else
                        <span class="badge-muted">Inactive</span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Store</p>
                <p class="mt-1 text-sm text-ink">{{ $user->store->name ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium uppercase text-muted">Joined</p>
                <p class="mt-1 text-sm text-ink">{{ $user->created_at?->format('d M Y') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
