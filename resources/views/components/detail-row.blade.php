@props(['label', 'value'])

@php
    $value = (string) $value;
    $isUrl = \Illuminate\Support\Str::startsWith($value, ['https://', 'http://']);
@endphp

{{-- One label/value line of a details list. Values sit on the right and wrap there; a url becomes a link without its scheme. --}}
<div data-detail-row {{ $attributes->class('flex justify-between items-baseline gap-6 border-b border-neutral-200 dark:border-neutral-800 pb-3') }}>
    <span class="shrink-0 text-xs text-neutral-500 tracking-[1.2px] uppercase">{{ $label }}</span>
    @if($isUrl)
        <a href="{{ $value }}" target="_blank" rel="noopener" class="min-w-0 text-right text-sm font-medium text-neutral-950 dark:text-neutral-50 break-words underline underline-offset-4 decoration-neutral-300 dark:decoration-neutral-700 hover:decoration-neutral-950 dark:hover:decoration-neutral-50">{{ rtrim(\Illuminate\Support\Str::after($value, '://'), '/') }}</a>
    @else
        <span class="min-w-0 text-right text-sm font-medium text-neutral-950 dark:text-neutral-50 break-words">{{ $value }}</span>
    @endif
</div>
