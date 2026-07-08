{{-- Ambient decorative background: floating gradient blobs + a soft wave, purely visual --}}
<div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
    <div class="decor-blob -left-24 -top-24 h-72 w-72 animate-float-slow" style="background: radial-gradient(circle, #8B5CF6, transparent 70%);"></div>
    <div class="decor-blob -right-20 top-10 h-80 w-80 animate-float" style="background: radial-gradient(circle, #06B6D4, transparent 70%);"></div>
    <div class="decor-blob left-1/3 top-1/2 h-64 w-64 animate-float-slow" style="background: radial-gradient(circle, #EC4899, transparent 70%);"></div>
    <div class="decor-blob -bottom-24 -right-10 h-96 w-96 animate-float" style="background: radial-gradient(circle, #FB923C, transparent 70%);"></div>
    <div class="decor-blob bottom-10 left-10 h-56 w-56 animate-float-slow" style="background: radial-gradient(circle, #10B981, transparent 70%);"></div>

    <svg class="absolute bottom-0 left-0 w-full opacity-40 dark:opacity-10" viewBox="0 0 1440 220" preserveAspectRatio="none" fill="none">
        <path d="M0,120 C240,200 480,40 720,90 C960,140 1200,200 1440,110 L1440,220 L0,220 Z" fill="url(#decorWaveGradient)" />
        <defs>
            <linearGradient id="decorWaveGradient" x1="0" y1="0" x2="1440" y2="0" gradientUnits="userSpaceOnUse">
                <stop offset="0%" stop-color="#4F46E5" stop-opacity="0.10" />
                <stop offset="50%" stop-color="#06B6D4" stop-opacity="0.10" />
                <stop offset="100%" stop-color="#EC4899" stop-opacity="0.10" />
            </linearGradient>
        </defs>
    </svg>
</div>
