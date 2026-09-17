@props([
    'key',                  // identifies the row: email, phone, github, address…
    'label',
    'value',
    'href' => null,         // without it the row is plain text and gets no arrow
    'icon' => null,         // lucide icon name
    'platform' => null,     // or a social platform, drawn by x-social-icon
    'external' => false,
])

@php($tag = $href ? 'a' : 'div')

<{{ $tag }} data-contact-row="{{ $key }}"
    @if($href) href="{{ $href }}" @endif
    @if($href && $external) target="_blank" rel="noopener" @endif
    @class(['py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center gap-4', 'transition-all hover:pl-1 group' => $href])>
    <span class="flex items-center gap-3 min-w-0">
        <span data-contact-row-icon class="shrink-0 text-neutral-400 dark:text-neutral-600">
            @if($platform)
                <x-social-icon :platform="$platform" class="w-5 h-5" />
            @else
                <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
            @endif
        </span>
        <span class="min-w-0">
            <span class="block text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">{{ $label }}</span>
            <span class="block text-base font-medium text-neutral-950 dark:text-neutral-50 break-words">{{ $value }}</span>
        </span>
    </span>
    @if($href)
        <span class="shrink-0 text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
            <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
        </span>
    @endif
</{{ $tag }}>
