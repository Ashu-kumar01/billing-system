@props(['type' => 'empty', 'class' => 'w-48 h-48'])

@php
    $palettes = [
        'dashboard'  => ['from' => '#4F46E5', 'to' => '#06B6D4'],
        'invoice'    => ['from' => '#4F46E5', 'to' => '#8B5CF6'],
        'payment'    => ['from' => '#10B981', 'to' => '#06B6D4'],
        'analytics'  => ['from' => '#3B82F6', 'to' => '#8B5CF6'],
        'customer'   => ['from' => '#EC4899', 'to' => '#8B5CF6'],
        'inventory'  => ['from' => '#FB923C', 'to' => '#FACC15'],
        'expense'    => ['from' => '#EF4444', 'to' => '#FB923C'],
        'settings'   => ['from' => '#64748B', 'to' => '#3B82F6'],
        'empty'      => ['from' => '#8B5CF6', 'to' => '#3B82F6'],
        'not-found'  => ['from' => '#EC4899', 'to' => '#FB923C'],
        'no-data'    => ['from' => '#0EA5E9', 'to' => '#8B5CF6'],
        'loading'    => ['from' => '#06B6D4', 'to' => '#4F46E5'],
    ];
    $palette = $palettes[$type] ?? $palettes['empty'];
    $gid = 'ill-'.$type.'-'.uniqid();
@endphp

<svg viewBox="0 0 240 200" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes->merge(['class' => $class]) }}>
    <defs>
        <linearGradient id="{{ $gid }}" x1="0" y1="0" x2="240" y2="200" gradientUnits="userSpaceOnUse">
            <stop offset="0%" stop-color="{{ $palette['from'] }}" />
            <stop offset="100%" stop-color="{{ $palette['to'] }}" />
        </linearGradient>
    </defs>

    {{-- shared soft blob backdrop --}}
    <ellipse cx="120" cy="110" rx="98" ry="78" fill="url(#{{ $gid }})" opacity="0.12" />
    <circle cx="45" cy="45" r="10" fill="url(#{{ $gid }})" opacity="0.35" />
    <circle cx="205" cy="150" r="7" fill="url(#{{ $gid }})" opacity="0.35" />
    <circle cx="200" cy="40" r="5" fill="url(#{{ $gid }})" opacity="0.5" />

    @switch($type)
        @case('dashboard')
            <rect x="55" y="60" width="55" height="45" rx="8" fill="url(#{{ $gid }})" opacity="0.85" />
            <rect x="118" y="60" width="65" height="22" rx="6" fill="url(#{{ $gid }})" opacity="0.5" />
            <rect x="118" y="88" width="65" height="17" rx="6" fill="url(#{{ $gid }})" opacity="0.3" />
            <rect x="55" y="112" width="128" height="30" rx="8" fill="url(#{{ $gid }})" opacity="0.2" />
            <path d="M63 135 L80 118 L95 128 L112 105" stroke="url(#{{ $gid }})" stroke-width="3" stroke-linecap="round" fill="none" />
            @break

        @case('invoice')
            <rect x="72" y="42" width="96" height="122" rx="10" fill="white" stroke="url(#{{ $gid }})" stroke-width="3" />
            <rect x="90" y="62" width="60" height="8" rx="4" fill="url(#{{ $gid }})" opacity="0.8" />
            <rect x="90" y="80" width="60" height="5" rx="2.5" fill="url(#{{ $gid }})" opacity="0.35" />
            <rect x="90" y="92" width="45" height="5" rx="2.5" fill="url(#{{ $gid }})" opacity="0.35" />
            <rect x="90" y="112" width="60" height="1.5" fill="url(#{{ $gid }})" opacity="0.25" />
            <rect x="90" y="124" width="35" height="6" rx="3" fill="url(#{{ $gid }})" opacity="0.5" />
            <rect x="110" y="138" width="40" height="10" rx="5" fill="url(#{{ $gid }})" />
            @break

        @case('payment')
            <rect x="55" y="70" width="130" height="80" rx="14" fill="url(#{{ $gid }})" opacity="0.9" />
            <rect x="55" y="88" width="130" height="16" fill="white" opacity="0.85" />
            <rect x="70" y="122" width="40" height="10" rx="5" fill="white" opacity="0.9" />
            <circle cx="150" cy="127" r="10" fill="white" opacity="0.6" />
            <circle cx="165" cy="127" r="10" fill="white" opacity="0.4" />
            @break

        @case('analytics')
            <rect x="60" y="110" width="20" height="40" rx="4" fill="url(#{{ $gid }})" opacity="0.5" />
            <rect x="90" y="90" width="20" height="60" rx="4" fill="url(#{{ $gid }})" opacity="0.7" />
            <rect x="120" y="65" width="20" height="85" rx="4" fill="url(#{{ $gid }})" />
            <rect x="150" y="100" width="20" height="50" rx="4" fill="url(#{{ $gid }})" opacity="0.6" />
            <path d="M58 78 L95 55 L125 68 L172 35" stroke="url(#{{ $gid }})" stroke-width="3" stroke-linecap="round" fill="none" opacity="0.8" />
            @break

        @case('customer')
            <circle cx="120" cy="75" r="26" fill="url(#{{ $gid }})" opacity="0.85" />
            <path d="M70 158 C70 122 96 108 120 108 C144 108 170 122 170 158 Z" fill="url(#{{ $gid }})" opacity="0.55" />
            @break

        @case('inventory')
            <rect x="65" y="95" width="45" height="45" rx="6" fill="url(#{{ $gid }})" opacity="0.9" />
            <rect x="118" y="95" width="45" height="45" rx="6" fill="url(#{{ $gid }})" opacity="0.55" />
            <rect x="92" y="55" width="45" height="45" rx="6" fill="url(#{{ $gid }})" opacity="0.75" />
            @break

        @case('expense')
            <rect x="78" y="40" width="84" height="128" rx="8" fill="white" stroke="url(#{{ $gid }})" stroke-width="3" />
            <path d="M78 168 L86 160 L94 168 L102 160 L110 168 L118 160 L126 168 L134 160 L142 168 L150 160 L158 168 L162 160" stroke="url(#{{ $gid }})" stroke-width="2" fill="none" />
            <rect x="94" y="58" width="52" height="6" rx="3" fill="url(#{{ $gid }})" opacity="0.7" />
            <rect x="94" y="72" width="35" height="5" rx="2.5" fill="url(#{{ $gid }})" opacity="0.35" />
            <rect x="94" y="90" width="52" height="1.5" fill="url(#{{ $gid }})" opacity="0.25" />
            <rect x="94" y="102" width="52" height="1.5" fill="url(#{{ $gid }})" opacity="0.25" />
            <rect x="94" y="114" width="52" height="1.5" fill="url(#{{ $gid }})" opacity="0.25" />
            @break

        @case('settings')
            <circle cx="120" cy="105" r="30" fill="none" stroke="url(#{{ $gid }})" stroke-width="10" opacity="0.85" />
            <circle cx="120" cy="105" r="10" fill="url(#{{ $gid }})" />
            <g stroke="url(#{{ $gid }})" stroke-width="8" stroke-linecap="round" opacity="0.7">
                <line x1="120" y1="55" x2="120" y2="65" />
                <line x1="120" y1="145" x2="120" y2="155" />
                <line x1="70" y1="105" x2="80" y2="105" />
                <line x1="160" y1="105" x2="170" y2="105" />
            </g>
            @break

        @case('not-found')
            <text x="120" y="118" text-anchor="middle" font-size="56" font-weight="800" fill="url(#{{ $gid }})" opacity="0.9" font-family="Figtree, sans-serif">404</text>
            <circle cx="120" cy="150" r="4" fill="url(#{{ $gid }})" opacity="0.6" />
            @break

        @case('loading')
            <circle cx="120" cy="105" r="34" fill="none" stroke="url(#{{ $gid }})" stroke-width="8" opacity="0.18" />
            <path d="M120 71 A34 34 0 0 1 154 105" stroke="url(#{{ $gid }})" stroke-width="8" stroke-linecap="round" fill="none">
                <animateTransform attributeName="transform" type="rotate" from="0 120 105" to="360 120 105" dur="0.9s" repeatCount="indefinite" />
            </path>
            @break

        @case('no-data')
            <path d="M75 90 L120 65 L165 90 L165 140 L75 140 Z" fill="none" stroke="url(#{{ $gid }})" stroke-width="4" stroke-linejoin="round" />
            <path d="M75 90 L120 115 L165 90" stroke="url(#{{ $gid }})" stroke-width="4" fill="none" stroke-linejoin="round" />
            <line x1="120" y1="115" x2="120" y2="140" stroke="url(#{{ $gid }})" stroke-width="4" />
            @break

        @default {{-- empty --}}
            <path d="M75 95 L120 70 L165 95 L165 145 L75 145 Z" fill="url(#{{ $gid }})" opacity="0.15" />
            <path d="M75 95 L120 70 L165 95 L165 145 L75 145 Z" fill="none" stroke="url(#{{ $gid }})" stroke-width="4" stroke-linejoin="round" />
            <path d="M75 95 L120 120 L165 95" stroke="url(#{{ $gid }})" stroke-width="4" fill="none" stroke-linejoin="round" />
            <circle cx="150" cy="60" r="4" fill="url(#{{ $gid }})" opacity="0.6" />
    @endswitch
</svg>
