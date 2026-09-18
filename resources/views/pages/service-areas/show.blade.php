@extends('layouts.app')

@section('title', $area->name.' Web Tasarım ve Yazılım')
@section('meta_description', \App\Support\Seo::description($area->summary, $area->description, $area->name.' ve çevresindeki işletmeler için web tasarım, e-ticaret ve özel yazılım hizmeti.'))

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::serviceArea($area), \App\Support\Schema::business(), \App\Support\Schema::breadcrumbs(['Hizmet Bölgeleri' => \App\Support\Seo::route('service-areas'), $area->name => \App\Support\Seo::route('service-areas.show', $area)])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">

            {{-- Back Link --}}
            <div class="mb-12">
                <a href="{{ route('service-areas') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors inline-flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Tüm hizmet bölgeleri
                </a>
            </div>

            <x-eyebrow class="mb-3">{{ $area->province }} · Hizmet Bölgesi</x-eyebrow>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $area->name }} web tasarım ve yazılım.</h1>
            @if($area->summary)
                <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[640px]">{{ $area->summary }}</p>
            @endif
            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">{{ $area->name }} için konuşalım <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
            </div>

            {{-- Local content --}}
            <div class="mt-14 grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 lg:gap-16 items-start">
                <div>
                    @if($area->description)
                        <x-section-heading class="mb-5">{{ $area->name }} işletmeleri için ne yapıyorum</x-section-heading>
                        <p class="m-0 text-[17px] leading-[1.75] text-neutral-700 dark:text-neutral-300">{!! nl2br(e($area->description)) !!}</p>
                    @endif
                </div>

                @if(filled($area->sectors))
                    <aside class="lg:sticky lg:top-24">
                        <x-eyebrow size="sm" class="mb-4">Kimlerle çalışıyorum</x-eyebrow>
                        <ul class="m-0 p-0 list-none flex flex-col border-t border-neutral-200 dark:border-neutral-800">
                            @foreach($area->sectors as $sector)
                                <li class="py-3 border-b border-neutral-200 dark:border-neutral-800 text-sm text-neutral-700 dark:text-neutral-300">{{ $sector }}</li>
                            @endforeach
                        </ul>
                    </aside>
                @endif
            </div>

            {{-- Services --}}
            @if($services->isNotEmpty())
                <div class="mt-16 flex items-baseline justify-between gap-6 mb-7">
                    <x-section-heading>{{ $area->name }} için sunduğum hizmetler</x-section-heading>
                    <a href="{{ route('services') }}" class="shrink-0 inline-flex items-center gap-1.5 text-[13px] font-medium text-neutral-950 dark:text-neutral-50 transition-opacity hover:opacity-70">Kapsam ve fiyatlar <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($services as $service)
                        <div class="p-6 rounded-2xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">
                            <h3 class="m-0 mb-2 text-[17px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $service->title }}</h3>
                            <p class="m-0 text-[13px] leading-relaxed text-neutral-600 dark:text-neutral-400">{{ $service->description }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <x-local-working :provinces="$area->province" class="mt-16" />

            {{-- Other areas --}}
            @if($otherAreas->isNotEmpty())
                <x-section-heading class="mt-16 mb-5">Diğer hizmet bölgeleri</x-section-heading>
                <nav class="flex flex-wrap gap-2" aria-label="Diğer hizmet bölgeleri">
                    @foreach($otherAreas as $otherArea)
                        <a href="{{ route('service-areas.show', $otherArea) }}" class="px-3.5 py-2 text-[13px] font-medium rounded-full border border-neutral-200 dark:border-neutral-800 text-neutral-700 dark:text-neutral-300 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 hover:text-neutral-950 dark:hover:text-neutral-50">{{ $otherArea->name }}</a>
                    @endforeach
                </nav>
            @endif
        </div>
    </section>
@endsection
