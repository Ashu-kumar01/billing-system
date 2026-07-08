@props(['label', 'value', 'icon' => 'fa-chart-simple', 'color' => 'primary', 'trend' => null, 'trendUp' => true])

@php
    $colors = [
        'primary' => ['bg' => 'bg-primary-50 dark:bg-primary-500/15', 'text' => 'text-primary-600 dark:text-primary-500', 'ring' => 'from-primary-500 to-accent-500'],
        'secondary' => ['bg' => 'bg-secondary-50 dark:bg-secondary-500/15', 'text' => 'text-secondary-600 dark:text-secondary-500', 'ring' => 'from-secondary-500 to-sky-500'],
        'success' => ['bg' => 'bg-green-50 dark:bg-green-500/15', 'text' => 'text-success dark:text-green-400', 'ring' => 'from-mint-500 to-success'],
        'warning' => ['bg' => 'bg-amber-50 dark:bg-amber-500/15', 'text' => 'text-warning dark:text-amber-400', 'ring' => 'from-orange-500 to-yellow-500'],
        'danger' => ['bg' => 'bg-red-50 dark:bg-red-500/15', 'text' => 'text-danger dark:text-red-400', 'ring' => 'from-pink-500 to-danger'],
        'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-500/15', 'text' => 'text-purple-600 dark:text-purple-500', 'ring' => 'from-purple-500 to-primary-500'],
        'pink' => ['bg' => 'bg-pink-50 dark:bg-pink-500/15', 'text' => 'text-pink-600 dark:text-pink-500', 'ring' => 'from-pink-500 to-purple-500'],
        'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-500/15', 'text' => 'text-orange-600 dark:text-orange-500', 'ring' => 'from-orange-500 to-yellow-500'],
        'mint' => ['bg' => 'bg-mint-50 dark:bg-mint-500/15', 'text' => 'text-mint-600 dark:text-mint-500', 'ring' => 'from-mint-500 to-accent-500'],
        'sky' => ['bg' => 'bg-sky-50 dark:bg-sky-500/15', 'text' => 'text-sky-600 dark:text-sky-500', 'ring' => 'from-sky-500 to-accent-500'],
    ][$color] ?? ['bg' => 'bg-primary-50', 'text' => 'text-primary-600', 'ring' => 'from-primary-500 to-accent-500'];
@endphp

<div class="card group relative overflow-hidden p-5 transition-all duration-200 hover:-translate-y-1 hover:shadow-glow">
    <div class="pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br {{ $colors['ring'] }} opacity-[0.08] transition-transform duration-300 group-hover:scale-125"></div>

    <div class="relative flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-muted">{{ $label }}</p>
            <p class="mt-2 text-2xl font-bold text-ink">{{ $value }}</p>
        </div>
        <div class="flex h-11 w-11 items-center justify-center rounded-xl {{ $colors['bg'] }} {{ $colors['text'] }} text-lg shadow-soft">
            <i class="fa-solid {{ $icon }}"></i>
        </div>
    </div>

    @if ($trend !== null)
        <div class="relative mt-3 flex items-center gap-1 text-xs font-semibold {{ $trendUp ? 'text-success' : 'text-danger' }}">
            <i class="fa-solid {{ $trendUp ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
            <span>{{ $trend }}</span>
            <span class="font-normal text-muted">vs last period</span>
        </div>
    @endif
</div>
