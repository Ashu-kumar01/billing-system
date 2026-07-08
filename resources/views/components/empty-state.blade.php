@props(['icon' => 'fa-inbox', 'title' => 'No records found', 'subtitle' => 'Get started by creating a new item.', 'illustration' => 'no-data'])

<div class="flex flex-col items-center justify-center gap-3 py-14 text-center">
    <x-illustration :type="$illustration" class="w-40 h-40 sm:w-48 sm:h-48" />
    <div>
        <p class="text-base font-semibold text-ink">{{ $title }}</p>
        <p class="mt-1 text-sm text-muted">{{ $subtitle }}</p>
    </div>
    {{ $slot ?? '' }}
</div>
