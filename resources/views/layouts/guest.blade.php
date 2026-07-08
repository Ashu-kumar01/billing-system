<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Billing System') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen flex flex-col sm:flex-row">
        <div class="relative hidden sm:flex sm:w-1/2 flex-col justify-between overflow-hidden p-12 text-white" style="background-image: linear-gradient(160deg, #4F46E5 0%, #06B6D4 100%);">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div class="absolute -left-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-3xl animate-float-slow"></div>
                <div class="absolute right-0 top-1/3 h-56 w-56 rounded-full bg-pink-400/20 blur-3xl animate-float"></div>
                <div class="absolute -bottom-20 left-1/4 h-72 w-72 rounded-full bg-yellow-300/10 blur-3xl animate-float-slow"></div>
                <svg class="absolute bottom-0 left-0 w-full opacity-30" viewBox="0 0 600 160" preserveAspectRatio="none" fill="none">
                    <path d="M0,90 C150,150 300,20 450,70 C520,90 570,120 600,90 L600,160 L0,160 Z" fill="white" fill-opacity="0.12" />
                </svg>
            </div>

            <div class="relative flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <span class="text-lg font-bold">{{ config('app.name', 'Billing System') }}</span>
            </div>

            <div class="relative">
                <x-illustration type="dashboard" class="w-56 h-56 mb-6 drop-shadow-xl" />
                <h1 class="text-3xl font-bold leading-tight">Manage billing, invoices &amp; growth — all in one place.</h1>
                <p class="mt-4 text-white/80">A premium, modern platform to run your customers, products, invoices and reports with ease.</p>
            </div>

            <p class="relative text-sm text-white/60">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>

        <div class="relative flex flex-1 flex-col items-center justify-center p-6 sm:p-12">
            <x-decor-blobs />

            <div class="relative w-full max-w-md">
                <div class="mb-8 flex justify-center sm:hidden">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl text-white shadow-glow" style="background-image: linear-gradient(135deg, #4F46E5 0%, #06B6D4 100%);">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                </div>

                <div class="glass p-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
