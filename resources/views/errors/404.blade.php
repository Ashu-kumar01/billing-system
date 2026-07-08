<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found - {{ config('app.name', 'Billing System') }}</title>

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
    <div class="relative flex min-h-screen flex-col items-center justify-center p-6 text-center">
        <x-decor-blobs />

        <div class="glass relative max-w-md p-10">
            <x-illustration type="not-found" class="mx-auto h-48 w-48" />
            <h1 class="mt-2 text-xl font-bold text-ink">Page not found</h1>
            <p class="mt-2 text-sm text-muted">The page you're looking for doesn't exist or may have been moved.</p>

            <a href="{{ url('/') }}" class="btn-primary mt-6 inline-flex">
                <i class="fa-solid fa-house"></i> Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
