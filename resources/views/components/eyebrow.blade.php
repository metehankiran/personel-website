@props(['size' => 'md', 'accent' => true])

{{-- Small uppercase label. "md" sits above page titles and carries an accent line (pass :accent="false" to bring your own marker); "sm" titles groups such as footer columns, sidebars and cards. --}}
<div {{ $attributes->class([
    'font-semibold uppercase text-neutral-950 dark:text-neutral-50',
    'flex items-center gap-2.5 text-[13px] tracking-[1.6px]' => $size === 'md',
    'text-xs tracking-[1.4px]' => $size === 'sm',
]) }}>
    @if($size === 'md' && $accent)
        <span data-eyebrow-accent class="w-6 h-px bg-neutral-950 dark:bg-neutral-50 shrink-0" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</div>
