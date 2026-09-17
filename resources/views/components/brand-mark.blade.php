@props([
    'logo' => false,        // true where an uploaded logo may replace the ring (header, footer)
    'logoClass' => 'h-7 max-w-[120px]',
])

@php
    $url = fn (?string $path): ?string => $logo && \App\Support\Images::exists($path) ? \App\Support\Images::url($path) : null;

    $light = $url($general->logo_path);
    $dark = $url($general->logo_dark_path);

    // Height is fixed by CSS and width is auto, so without the real size the brand text jumps once the file loads.
    $size = function (?string $path): string {
        $dimensions = \App\Support\Images::dimensions($path);

        return $dimensions ? 'width="'.$dimensions['width'].'" height="'.$dimensions['height'].'"' : '';
    };
@endphp

{{--
    The site theme is switched with <html data-theme>, which an <img> cannot see,
    so both logos are printed and CSS decides which one shows.
--}}
@if($light && $dark)
    <img data-site-logo="light" src="{{ $light }}" alt="" {!! $size($general->logo_path) !!} class="{{ $logoClass }} w-auto object-contain shrink-0 dark:hidden" />
    <img data-site-logo="dark" src="{{ $dark }}" alt="" {!! $size($general->logo_dark_path) !!} class="{{ $logoClass }} w-auto object-contain shrink-0 hidden dark:block" />
@elseif($light || $dark)
    <img data-site-logo src="{{ $light ?? $dark }}" alt="" {!! $size($light ? $general->logo_path : $general->logo_dark_path) !!} class="{{ $logoClass }} w-auto object-contain shrink-0" />
@else
    {{-- Ring: outer radius 24, inner radius 16 (thickness is one sixth of the diameter). --}}
    <svg data-brand-ring viewBox="0 0 48 48" aria-hidden="true" {{ $attributes->class(['shrink-0 text-neutral-950 dark:text-neutral-50']) }}>
        <path fill="currentColor" fill-rule="evenodd" d="M24 0a24 24 0 1 0 0 48a24 24 0 1 0 0-48zm0 8a16 16 0 1 1 0 32a16 16 0 1 1 0-32z" />
    </svg>
@endif
