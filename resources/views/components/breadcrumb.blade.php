@props(['items' => []])

<nav class="mb-6 flex flex-wrap items-center gap-1.5 text-sm">
    <a href="{{ route('dashboard') }}" class="text-muted hover:text-primary-600">
        <i class="fa-solid fa-house text-xs"></i>
    </a>
    @foreach ($items as $label => $url)
        <i class="fa-solid fa-chevron-right text-[10px] text-muted"></i>
        @if ($url && !$loop->last)
            <a href="{{ $url }}" class="text-muted hover:text-primary-600">{{ $label }}</a>
        @else
            <span class="font-medium text-ink">{{ $label }}</span>
        @endif
    @endforeach
</nav>
