@props([
    'title',
    'icon' => 'inbox',
    'description' => null,
    'actionLabel' => null,
    'actionUrl' => null,
])

{{-- Shown where a list has nothing to show yet, so the section keeps its place instead of vanishing. --}}
<div data-empty-state {{ $attributes->class(['flex flex-col items-center text-center px-6 py-14 rounded-2xl border border-dashed border-neutral-300 dark:border-neutral-700 bg-neutral-50/60 dark:bg-neutral-900/40']) }}>
    <span class="inline-flex w-11 h-11 items-center justify-center rounded-full bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 text-neutral-500 dark:text-neutral-400">
        <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
    </span>
    <p class="m-0 mt-4 text-[15px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $title }}</p>
    @if(filled($description))
        <p class="m-0 mt-1.5 max-w-[420px] text-sm leading-relaxed text-neutral-600 dark:text-neutral-400">{{ $description }}</p>
    @endif
    @if(filled($actionLabel) && filled($actionUrl))
        <a href="{{ $actionUrl }}" class="mt-5 inline-flex items-center gap-1.5 text-[13px] font-medium text-neutral-950 dark:text-neutral-50 underline underline-offset-4 decoration-neutral-300 dark:decoration-neutral-700 hover:decoration-current transition-colors">{{ $actionLabel }} <span aria-hidden="true">→</span></a>
    @endif
</div>
