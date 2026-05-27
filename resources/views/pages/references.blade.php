@extends('layouts.app')

@section('title', 'Referanslar — Metehan Kıran')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Referanslar</div>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Birlikte çalıştığım insanlar.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">40+ müşteriden bir kesit. Hepsi gerçek, isim+rol kontrol edilebilir.</p>

            {{-- Testimonials --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-14">
                @foreach($testimonials as $testimonial)
                    <div class="p-7 rounded-xl border border-neutral-200 dark:border-neutral-800 flex flex-col {{ $loop->first ? 'sm:col-span-2 bg-neutral-100 dark:bg-neutral-900' : 'bg-neutral-50 dark:bg-neutral-900' }}">
                        <p class="m-0 flex-1 opacity-95 leading-relaxed {{ $loop->first ? 'text-[22px] font-medium tracking-tight text-neutral-950 dark:text-neutral-50' : 'text-[15px] text-neutral-950 dark:text-neutral-50' }}">
                            <span class="text-[48px] leading-none font-serif text-neutral-200 dark:text-neutral-800 float-left mr-2 -mt-1">"</span>
                            {{ $testimonial->body }}
                        </p>
                        <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-800 flex items-center gap-3">
                            @if($testimonial->avatar)
                                <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover" width="40" height="40" loading="lazy">
                            @endif
                            <div>
                                <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $testimonial->name }}</div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Brands --}}
            @if($brands->isNotEmpty())
                <div class="mt-20">
                    <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-5">Birlikte çalıştığım markalar</div>
                    <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-6 gap-px bg-neutral-200 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-800 overflow-hidden">
                        @foreach($brands as $brand)
                            @if($brand->url)
                                <a href="{{ $brand->url }}" class="aspect-[3/1] bg-white dark:bg-neutral-950 flex items-center justify-center overflow-hidden hover:bg-neutral-50 dark:hover:bg-neutral-900 transition-colors" target="_blank" rel="noopener">
                            @else
                                <div class="aspect-[3/1] bg-white dark:bg-neutral-950 flex items-center justify-center overflow-hidden">
                            @endif
                                @if($brand->logo)
                                    <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <span class="text-[15px] font-medium text-neutral-600 dark:text-neutral-400 font-serif px-4 text-center">{{ $brand->name }}</span>
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
        </div>
    </section>
@endsection
