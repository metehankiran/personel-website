@extends('layouts.app')

@section('title', 'Referanslar')
@section('meta_description', 'Birlikte çalışılan markalar ve müşterilerin kendi sözleriyle yorumları. '.$general->author_name.' ile çalışmak nasıl, doğrudan onlardan okuyun.')

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::breadcrumbs(['Referanslar' => \App\Support\Seo::route('references')])) }}
@endpush

@section('content')
    @php
        $initials = fn (string $name): string => collect(explode(' ', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $word): string => mb_strtoupper(mb_substr($word, 0, 1)))
            ->join('');

        $featured = $testimonials->first();
        $others = $testimonials->skip(1);
    @endphp

    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-eyebrow class="mb-3">Referanslar</x-eyebrow>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Birlikte çalıştığım insanlar.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">40+ müşteriden bir kesit. Hepsi gerçek, isim+rol kontrol edilebilir.</p>

            {{-- Brands strip --}}
            @if($brands->isNotEmpty())
                <div class="mt-14">
                    <x-section-heading class="mb-6">Birlikte çalıştığım markalar</x-section-heading>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-px bg-neutral-200 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-800 rounded-2xl overflow-hidden">
                        @foreach($brands as $brand)
                            @php $brandClasses = 'group aspect-[5/2] bg-white dark:bg-neutral-950 flex items-center justify-center px-6 transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-900'; @endphp
                            @if($brand->url)
                                <a href="{{ $brand->url }}" class="{{ $brandClasses }}" target="_blank" rel="noopener" title="{{ $brand->name }}">
                            @else
                                <div class="{{ $brandClasses }}" title="{{ $brand->name }}">
                            @endif
                                @if(\App\Support\Images::exists($brand->logo))
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="max-h-10 w-auto max-w-[70%] object-contain grayscale opacity-60 transition duration-300 group-hover:grayscale-0 group-hover:opacity-100" loading="lazy">
                                @else
                                    <span class="text-[15px] font-medium font-serif text-neutral-500 dark:text-neutral-400 text-center transition-colors group-hover:text-neutral-950 dark:group-hover:text-neutral-50">{{ $brand->name }}</span>
                                @endif
                            @if($brand->url)
                                </a>
                            @else
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Featured testimonial --}}
            @if($featured)
                <figure class="m-0 mt-20 relative rounded-3xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 px-8 sm:px-14 py-12 sm:py-16 overflow-hidden" data-featured>
                    <span aria-hidden="true" class="absolute -top-6 left-6 sm:left-10 text-[180px] leading-none font-serif text-neutral-200/80 dark:text-neutral-800 select-none">"</span>
                    <blockquote class="relative m-0 max-w-[900px] text-[24px] sm:text-[30px] lg:text-[36px] leading-[1.25] font-medium tracking-tight text-balance text-neutral-950 dark:text-neutral-50">
                        {{ $featured->body }}
                    </blockquote>
                    <figcaption class="relative mt-10 flex items-center gap-4">
                        @if($featured->avatar)
                            <x-image :src="$featured->avatar" fallback="avatar" :alt="$featured->name" class="w-14 h-14 rounded-full object-cover ring-4 ring-white dark:ring-neutral-950" width="56" height="56" />
                        @else
                            <span class="w-14 h-14 rounded-full bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 text-base font-semibold inline-flex items-center justify-center ring-4 ring-white dark:ring-neutral-950">{{ $initials($featured->name) }}</span>
                        @endif
                        <div>
                            <div class="text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $featured->name }}</div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $featured->title }}{{ $featured->company ? ', '.$featured->company : '' }}</div>
                        </div>
                    </figcaption>
                </figure>
            @endif

            {{-- Remaining testimonials (masonry) --}}
            @if($others->isNotEmpty())
                <div class="mt-5 columns-1 sm:columns-2 lg:columns-3 gap-5 [&>*]:mb-5">
                    @foreach($others as $testimonial)
                        <figure class="m-0 break-inside-avoid flex flex-col p-7 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-[0_1px_2px_rgba(0,0,0,0.04),0_12px_32px_-16px_rgba(0,0,0,0.12)] dark:shadow-none transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <i data-lucide="quote" class="w-5 h-5 text-neutral-300 dark:text-neutral-700 mb-4"></i>
                            <blockquote class="m-0 flex-1 text-[15px] leading-relaxed text-neutral-800 dark:text-neutral-100">{{ $testimonial->body }}</blockquote>
                            <figcaption class="mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center gap-3">
                                @if($testimonial->avatar)
                                    <x-image :src="$testimonial->avatar" fallback="avatar" :alt="$testimonial->name" class="w-10 h-10 rounded-full object-cover" width="40" height="40" />
                                @else
                                    <span class="w-10 h-10 rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 text-sm font-semibold inline-flex items-center justify-center">{{ $initials($testimonial->name) }}</span>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $testimonial->name }}</div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                                </div>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
