<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' - ' : '' }}{{ config('app.name', 'Billing System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        // Applied before first paint to avoid a light-mode flash on load.
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-ink dark:bg-slate-950 dark:text-slate-100" x-data="{ sidebarOpen: false }">

    <x-decor-blobs />

    <div class="min-h-screen flex">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col min-w-0 lg:pl-72">
            @include('layouts.topbar')

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (isset($breadcrumbs))
                    <x-breadcrumb :items="$breadcrumbs" />
                @endif

                @if (session('success'))
                    <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 flex items-center gap-2 animate-fade-in-up dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 flex items-center gap-2 animate-fade-in-up dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                    </div>
                @endif

                <div class="animate-fade-in-up">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
