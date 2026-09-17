@extends('layouts.app')

@section('robots', 'noindex, follow')
@section('title', '404 — Sayfa kayıp')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section class="py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 text-center">
            <div class="text-[96px] font-medium tracking-tighter text-neutral-300 dark:text-neutral-800 font-sans tabular-nums leading-none">404</div>
            <h1 class="text-[44px] sm:text-[56px] lg:text-[72px] leading-none font-medium tracking-tighter text-neutral-950 dark:text-neutral-50 mt-6 m-0">Sayfa kayıp.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-6 max-w-[480px] mx-auto">Yanlış bir URL'ye geldin ya da ben taşımış olabilirim. Önemli olan iyiyiz.</p>
            <div class="mt-10 inline-flex flex-wrap gap-3 justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">Ana sayfaya dön <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                <button type="button" class="inline-flex items-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-transparent text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 cursor-pointer font-sans" data-search-trigger>
                    <i data-lucide="search" class="w-4 h-4"></i> Ara…
                    <kbd class="ml-1 text-[11px] px-1.5 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-500 border border-neutral-200 dark:border-neutral-800">⌘K</kbd>
                </button>
            </div>

            <div class="mt-16 pt-8 border-t border-neutral-200 dark:border-neutral-800">
                <x-section-heading class="mb-6">Belki bunlardan birini arıyordun</x-section-heading>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-left">
                    <a href="{{ $link('projects') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="folder-open" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">Projeler</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">5 yıllık seçili işler</div>
                    </a>
                    <a href="{{ $link('blog') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="notebook-pen" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">Blog</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Teknik yazılar</div>
                    </a>
                    <a href="{{ $link('contact') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="mail" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">İletişim</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Hızlı ulaş</div>
                    </a>
                    <a href="{{ $link('services') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="handshake" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">Hizmetler</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Çalışma şekilleri</div>
                    </a>
                    <a href="{{ $link('stack') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="layers" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">Teknolojiler</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Araçlar ve deneyim sürem</div>
                    </a>
                    <a href="{{ $link('references') }}" class="group p-5 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                        <div class="flex items-center gap-2.5 mb-1.5">
                            <i data-lucide="quote" class="w-4 h-4 text-neutral-400 dark:text-neutral-600"></i>
                            <h3 class="m-0 text-sm font-semibold text-neutral-950 dark:text-neutral-50">Referanslar</h3>
                        </div>
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">Müşteri yorumları</div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
