@props([
    'name',
    'options' => [],
    'id' => null,
    'selected' => null,
    'placeholder' => 'Seçiniz',
    'invalid' => false,
])

@php
    $id ??= 'select-'.$name;
    $selected = filled($selected) ? (string) $selected : '';
    $items = collect($options)->map(fn ($label, $value) => ['value' => (string) $value, 'label' => (string) $label])->values();
    $selectedLabel = $items->firstWhere('value', $selected)['label'] ?? null;
@endphp

{{-- Custom listbox: native <select> popups cannot be styled. Behaviour lives in Alpine.data('mkSelect') (public/theme/js/main.js). --}}
<div {{ $attributes->whereStartsWith(['wire:model', 'x-model'])->merge(['class' => 'relative']) }}
    x-data="mkSelect({ value: @js($selected), options: @js($items), placeholder: @js($placeholder) })"
    x-modelable="value"
    x-on:click.outside="close()">

    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" x-bind:value="value" />

    <button type="button" id="{{ $id }}" x-ref="button"
        role="combobox" aria-haspopup="listbox" aria-controls="{{ $id }}-listbox"
        aria-expanded="false" x-bind:aria-expanded="open.toString()"
        x-bind:aria-activedescendant="open && activeIndex >= 0 ? '{{ $id }}-option-' + activeIndex : null"
        @if($invalid) aria-invalid="true" @endif
        x-on:click="toggle()"
        x-on:keydown="onButtonKeydown($event)"
        @class([
            'w-full flex items-center justify-between gap-3 px-3.5 py-2.5 text-sm font-sans text-left bg-white dark:bg-neutral-950 border rounded-lg outline-none transition-colors cursor-pointer',
            'focus-visible:border-neutral-950 dark:focus-visible:border-neutral-50',
            'border-neutral-200 dark:border-neutral-800' => ! $invalid,
            'border-red-400 dark:border-red-500' => $invalid,
        ])
        x-bind:class="open && 'border-neutral-950 dark:border-neutral-50'">
        <span class="truncate {{ $selectedLabel ? 'text-neutral-950 dark:text-neutral-50' : 'text-neutral-400 dark:text-neutral-600' }}"
            x-bind:class="{ 'text-neutral-950 dark:text-neutral-50': selectedLabel, 'text-neutral-400 dark:text-neutral-600': ! selectedLabel }"
            x-text="selectedLabel || placeholder">{{ $selectedLabel ?? $placeholder }}</span>
        <svg class="w-4 h-4 shrink-0 text-neutral-400 transition-transform duration-200 motion-reduce:transition-none" x-bind:class="open && 'rotate-180'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
    </button>

    <ul id="{{ $id }}-listbox" x-ref="listbox" role="listbox" tabindex="-1" aria-labelledby="{{ $id }}"
        x-cloak x-show="open"
        x-transition:enter="transition ease-out duration-150 motion-reduce:transition-none"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100 motion-reduce:transition-none"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute z-30 left-0 right-0 mt-1.5 m-0 p-1 list-none max-h-64 overflow-y-auto overscroll-contain rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-neutral-950 shadow-[0_12px_32px_rgba(0,0,0,0.12)] dark:shadow-[0_12px_32px_rgba(0,0,0,0.6)]">
        @foreach($items as $index => $item)
            <li id="{{ $id }}-option-{{ $index }}" role="option"
                aria-selected="{{ $item['value'] === $selected ? 'true' : 'false' }}"
                x-bind:aria-selected="(value === @js($item['value'])).toString()"
                x-on:click="choose({{ $index }})"
                x-on:mousemove="activeIndex = {{ $index }}"
                x-bind:class="activeIndex === {{ $index }} ? 'bg-neutral-100 dark:bg-neutral-800/80 text-neutral-950 dark:text-neutral-50' : 'text-neutral-700 dark:text-neutral-300'"
                class="flex items-center justify-between gap-3 px-3 py-2 text-sm rounded-lg cursor-pointer select-none transition-colors">
                <span class="truncate" x-bind:class="value === @js($item['value']) && 'font-medium text-neutral-950 dark:text-neutral-50'">{{ $item['label'] }}</span>
                <svg x-cloak x-show="value === @js($item['value'])" class="w-4 h-4 shrink-0 text-neutral-950 dark:text-neutral-50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            </li>
        @endforeach
    </ul>
</div>
