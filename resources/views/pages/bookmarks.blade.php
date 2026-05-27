@extends('layouts.app')

@section('title', 'Yer İşaretlerim — Metehan Kıran')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Yer İşaretlerim</div>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Faydalı linkler.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[560px]">Çalışırken sık geri döndüğüm, başkalarına da değerli olabileceğini düşündüğüm kaynaklar. Konuya göre gruplanmış.</p>

            <div class="mt-16 flex flex-col gap-16">
                @foreach($categories as $category)
                    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 lg:gap-16 items-start">
                        <div class="lg:sticky lg:top-24">
                            <h2 class="m-0 text-[28px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $category->name }}</h2>
                            @if($category->description)
                                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed m-0 mt-2">{{ $category->description }}</p>
                            @endif
                        </div>
                        <div>
                            @foreach($category->bookmarks as $bookmark)
                                <a href="{{ $bookmark->url }}" class="grid grid-cols-1 sm:grid-cols-[280px_1fr] gap-1.5 sm:gap-6 items-baseline py-4 border-b border-neutral-200 dark:border-neutral-800 first:border-t transition-colors hover:opacity-80 group" target="_blank" rel="noopener">
                                    <span class="text-[15px] font-medium text-neutral-950 dark:text-neutral-50 inline-flex items-center gap-1.5">{{ $bookmark->domain }} <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-0 group-hover:opacity-60 transition-opacity"></i></span>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400">{{ $bookmark->description }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
