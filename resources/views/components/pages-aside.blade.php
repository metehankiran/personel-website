@props(['current' => null, 'updatedAt' => null])

@php
    $linkClass = 'flex items-center justify-between gap-3 py-3 border-b border-neutral-200 dark:border-neutral-800 text-sm transition-colors';
    $linkActive = 'font-medium text-neutral-950 dark:text-neutral-50';
    $linkIdle = 'text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50';

    // "current" is the slug of the static page being viewed, or "faq" on the questions page.
    $onFaq = $current === 'faq';
@endphp

{{-- Fills the row the way the about and blog sidebars do, so the page keeps the shared content box. --}}
<aside data-page-aside {{ $attributes->class(['lg:sticky lg:top-24']) }}>
    <x-eyebrow size="sm" class="mb-4">Sayfalar</x-eyebrow>
    <nav class="flex flex-col border-t border-neutral-200 dark:border-neutral-800" aria-label="Sayfalar">
        @foreach($menuPages as $menuPage)
            @php($isCurrent = $menuPage->slug === $current)
            <a href="{{ route('pages.show', $menuPage->slug) }}" {!! $isCurrent ? 'aria-current="page"' : '' !!} @class([$linkClass, $linkActive => $isCurrent, $linkIdle => ! $isCurrent])>{{ $menuPage->title }} <i data-lucide="arrow-right" class="w-3.5 h-3.5 shrink-0 opacity-50"></i></a>
        @endforeach
        <a href="{{ route('faq') }}" {!! $onFaq ? 'aria-current="page"' : '' !!} @class([$linkClass, $linkActive => $onFaq, $linkIdle => ! $onFaq])>Sıkça Sorulan Sorular <i data-lucide="arrow-right" class="w-3.5 h-3.5 shrink-0 opacity-50"></i></a>
    </nav>
    @if($updatedAt)
        <p class="mt-5 mb-0 text-xs text-neutral-500">Son güncelleme: <time datetime="{{ $updatedAt->toDateString() }}">{{ $updatedAt->translatedFormat('d F Y') }}</time></p>
    @endif
</aside>
