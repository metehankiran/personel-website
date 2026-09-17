@extends('layouts.app')

@section('robots', $page->isPublished() ? 'index, follow' : 'noindex, follow')
@section('title', $page->title)
@section('meta_description', \App\Support\Seo::description($page->body))

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::breadcrumbs([$page->title => \App\Support\Seo::route('pages.show', $page)])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="mb-12">
                <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $page->title }}</h1>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10 lg:gap-16 items-start">
                <div class="flex flex-col gap-[18px] text-[17px] leading-[1.7] opacity-90 max-w-[760px] [&>p]:m-0 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:text-2xl [&>h2]:font-semibold [&>h2]:tracking-tight [&>h2]:text-neutral-950 dark:[&>h2]:text-neutral-50 [&>ul]:pl-5 [&>ul]:list-disc [&_a]:underline [&_a]:underline-offset-2 [&_a]:hover:text-neutral-950 dark:[&_a]:hover:text-neutral-50 [&>ol]:pl-5 [&>ol]:list-decimal text-neutral-600 dark:text-neutral-400">
                    {!! $page->body !!}
                </div>

                {{-- Fills the row the way the about and blog sidebars do, so the page keeps the shared content box. --}}
                <aside data-page-aside class="lg:sticky lg:top-24">
                    <x-eyebrow size="sm" class="mb-4">Sayfalar</x-eyebrow>
                    <nav class="flex flex-col border-t border-neutral-200 dark:border-neutral-800" aria-label="Sayfalar">
                        @foreach($menuPages as $menuPage)
                            @php($isCurrent = $menuPage->slug === $page->slug)
                            <a href="{{ route('pages.show', $menuPage->slug) }}" {!! $isCurrent ? 'aria-current="page"' : '' !!} @class([
                                'flex items-center justify-between gap-3 py-3 border-b border-neutral-200 dark:border-neutral-800 text-sm transition-colors',
                                'font-medium text-neutral-950 dark:text-neutral-50' => $isCurrent,
                                'text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50' => ! $isCurrent,
                            ])>{{ $menuPage->title }} <i data-lucide="arrow-right" class="w-3.5 h-3.5 shrink-0 opacity-50"></i></a>
                        @endforeach
                        <a href="{{ route('faq') }}" class="flex items-center justify-between gap-3 py-3 border-b border-neutral-200 dark:border-neutral-800 text-sm text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Sıkça Sorulan Sorular <i data-lucide="arrow-right" class="w-3.5 h-3.5 shrink-0 opacity-50"></i></a>
                    </nav>
                    <p class="mt-5 mb-0 text-xs text-neutral-500">Son güncelleme: <time datetime="{{ $page->updated_at->toDateString() }}">{{ $page->updated_at->translatedFormat('d F Y') }}</time></p>
                </aside>
            </div>
        </div>
    </section>
@endsection
