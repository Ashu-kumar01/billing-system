@php
    $menu = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fa-gauge-high'],
        ['label' => 'Customers', 'route' => 'customers.index', 'icon' => 'fa-users'],
        ['label' => 'Products', 'route' => 'products.index', 'icon' => 'fa-box'],
        ['label' => 'Categories', 'route' => 'categories.index', 'icon' => 'fa-tags'],
        ['label' => 'Units', 'route' => 'units.index', 'icon' => 'fa-ruler'],
        ['label' => 'Invoices', 'route' => 'invoices.index', 'icon' => 'fa-file-invoice-dollar'],
        ['label' => 'Payments', 'route' => 'payments.index', 'icon' => 'fa-money-check-dollar'],
        ['label' => 'Expenses', 'route' => 'expenses.index', 'icon' => 'fa-receipt'],
        ['label' => 'Suppliers', 'route' => 'suppliers.index', 'icon' => 'fa-truck-field'],
        ['label' => 'Purchases', 'route' => 'purchases.index', 'icon' => 'fa-cart-shopping'],
        ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'fa-chart-line'],
        ['label' => 'Users', 'route' => 'users.index', 'icon' => 'fa-user-shield'],
        ['label' => 'Settings', 'route' => 'settings.edit', 'icon' => 'fa-gear'],
    ];
@endphp

<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-black/30 lg:hidden"
></div>

<aside
    class="fixed inset-y-0 left-0 z-40 w-72 transform border-r border-border bg-white/80 backdrop-blur-xl transition-transform duration-200 ease-in-out lg:translate-x-0 dark:bg-slate-900/90 dark:border-slate-800"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <div class="flex h-16 items-center gap-3 border-b border-border px-6 dark:border-slate-800">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl text-white shadow-glow" style="background-image: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);">
            <i class="fa-solid fa-bolt text-sm"></i>
        </div>
        <div class="leading-tight">
            <p class="text-sm font-bold text-ink dark:text-slate-100">{{ config('app.name', 'Billing System') }}</p>
            <p class="text-xs text-muted">SaaS Billing Suite</p>
        </div>
    </div>

    <nav class="flex flex-col gap-1 overflow-y-auto px-3 py-5" style="height: calc(100% - 4rem);">
        @foreach ($menu as $item)
            <a
                href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                class="nav-link-item {{ request()->routeIs(explode('.', $item['route'])[0].'.*') || request()->routeIs($item['route']) ? 'active' : '' }}"
            >
                <i class="fa-solid {{ $item['icon'] }} w-4 text-center text-base"></i>
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="nav-link-item w-full text-left hover:bg-red-50 hover:text-danger dark:hover:bg-red-500/10">
                <i class="fa-solid fa-right-from-bracket w-4 text-center text-base"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
