<header class="sticky top-0 z-20 flex h-16 items-center gap-4 border-b border-border bg-white/80 px-4 backdrop-blur sm:px-6 lg:px-8 dark:bg-slate-900/80 dark:border-slate-800">
    <button @click="sidebarOpen = true" class="rounded-lg p-2 text-muted hover:bg-surface-soft lg:hidden dark:hover:bg-slate-800">
        <i class="fa-solid fa-bars"></i>
    </button>

    <div class="hidden flex-1 max-w-md items-center gap-2 rounded-xl border border-border bg-surface-muted px-3.5 py-2 sm:flex dark:bg-slate-800 dark:border-slate-700">
        <i class="fa-solid fa-magnifying-glass text-muted text-sm"></i>
        <input type="text" placeholder="Search anything..." class="w-full border-0 bg-transparent p-0 text-sm text-ink placeholder:text-muted focus:ring-0 dark:text-slate-100">
    </div>

    <div class="ml-auto flex items-center gap-2 sm:gap-3">
        <span class="hidden md:flex items-center gap-2 rounded-xl border border-border px-3 py-1.5 text-xs font-medium text-muted dark:border-slate-700">
            <i class="fa-regular fa-calendar text-primary-500"></i> {{ now()->format('d M Y') }}
        </span>

        <button
            x-data="{ dark: document.documentElement.classList.contains('dark') }"
            @click="
                dark = !dark;
                document.documentElement.classList.toggle('dark', dark);
                localStorage.setItem('theme', dark ? 'dark' : 'light');
            "
            class="rounded-lg p-2.5 text-muted hover:bg-surface-soft dark:hover:bg-slate-800"
            title="Toggle dark mode"
        >
            <i class="fa-solid" :class="dark ? 'fa-sun' : 'fa-moon'"></i>
        </button>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="relative rounded-lg p-2.5 text-muted hover:bg-surface-soft dark:hover:bg-slate-800">
                <i class="fa-regular fa-bell"></i>
                <span class="absolute right-1.5 top-1.5 h-2 w-2 rounded-full bg-danger"></span>
            </button>
            <div x-show="open" x-cloak @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-72 rounded-xl border border-border bg-white p-3 shadow-card dark:bg-slate-900 dark:border-slate-800">
                <p class="px-1 pb-2 text-sm font-semibold text-ink dark:text-slate-100">Notifications</p>
                <div class="rounded-lg px-2 py-6 text-center text-sm text-muted">No new notifications</div>
            </div>
        </div>

        <button class="hidden sm:inline-flex rounded-lg p-2.5 text-muted hover:bg-surface-soft dark:hover:bg-slate-800">
            <i class="fa-regular fa-envelope"></i>
        </button>

        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 rounded-xl border border-border py-1.5 pl-1.5 pr-3 hover:bg-surface-soft dark:border-slate-700 dark:hover:bg-slate-800">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-50 text-sm font-semibold text-primary-700 dark:bg-primary-500/15 dark:text-primary-400">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <span class="hidden text-sm font-medium text-ink sm:block dark:text-slate-100">{{ auth()->user()->name ?? 'User' }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-muted"></i>
            </button>
            <div x-show="open" x-cloak @click.outside="open = false" x-transition class="absolute right-0 mt-2 w-56 rounded-xl border border-border bg-white p-2 shadow-card dark:bg-slate-900 dark:border-slate-800">
                <div class="px-3 py-2 border-b border-border mb-1 dark:border-slate-800">
                    <p class="text-sm font-semibold text-ink dark:text-slate-100">{{ auth()->user()->name ?? '' }}</p>
                    <p class="text-xs text-muted">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-ink hover:bg-surface-soft dark:text-slate-100 dark:hover:bg-slate-800">
                    <i class="fa-regular fa-user w-4"></i> Profile
                </a>
                <a href="{{ route('settings.edit') }}" class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm text-ink hover:bg-surface-soft dark:text-slate-100 dark:hover:bg-slate-800">
                    <i class="fa-solid fa-gear w-4"></i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-500/10">
                        <i class="fa-solid fa-right-from-bracket w-4"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
