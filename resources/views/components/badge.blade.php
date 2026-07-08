@props(['color' => 'muted'])

<span {{ $attributes->merge(['class' => "badge-{$color}"]) }}>{{ $slot }}</span>
