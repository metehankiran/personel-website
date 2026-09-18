@extends('layouts.app')

@php
    $provinceNames = $areas->pluck('province')->unique();
    $provinces = $provinceNames->join(', ', ' ve ');
@endphp

@section('title', $areas->isNotEmpty() ? $provinces.' Web Tasarım ve Yazılım: Hizmet Bölgeleri' : 'Hizmet Bölgeleri')
@section('meta_description', $areas->isNotEmpty() ? $provinces.' genelinde web tasarım, e-ticaret ve özel yazılım hizmeti: '.$areas->take(6)->pluck('name')->join(', ').($areas->count() > 6 ? ' ve diğer ilçeler.' : '.') : 'Web tasarım, e-ticaret ve özel yazılım hizmeti verdiğim il ve ilçeler.')

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::business(), \App\Support\Schema::breadcrumbs(['Hizmet Bölgeleri' => \App\Support\Seo::route('service-areas')])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-eyebrow class="mb-3">Hizmet Bölgeleri</x-eyebrow>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ match (true) {
                $areas->isEmpty() => 'Hizmet bölgeleri.',
                $provinceNames->count() === 1 => $provinces.' ve ilçelerinde web tasarım ve yazılım.',
                default => $provinces.' genelinde web tasarım ve yazılım.',
            } }}</h1>

            @if($areas->isEmpty())
                <x-empty-state class="mt-12" icon="map-pin" title="Henüz bir hizmet bölgesi eklenmedi" description="Yerinde ve uzaktan hizmet verdiğim il ve ilçeler burada yer alacak." action-label="Bana yaz" :action-url="route('contact')" />
            @else
                <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[640px]">Web sitesi, e-ticaret ve özel yazılım işlerinde {{ $provinces }} genelinde {{ $areas->count() }} bölgedeki işletmelerle yüz yüze ya da uzaktan çalışıyorum. Her ilçenin ekonomisi farklı; o yüzden her birine yaklaşımım da farklı.</p>

                {{-- Area links --}}
                <nav class="mt-10 flex flex-wrap gap-2" aria-label="Hizmet bölgeleri">
                    @foreach($areas as $area)
                        <a href="{{ route('service-areas.show', $area) }}" class="px-3.5 py-2 text-[13px] font-medium rounded-full border border-neutral-200 dark:border-neutral-800 text-neutral-700 dark:text-neutral-300 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 hover:text-neutral-950 dark:hover:text-neutral-50">{{ $area->name }}</a>
                    @endforeach
                </nav>

                {{-- Areas --}}
                <div class="mt-14 border-t border-neutral-200 dark:border-neutral-800">
                    @foreach($areas as $area)
                        <article class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-6 lg:gap-16 py-10 lg:py-12 border-b border-neutral-200 dark:border-neutral-800">
                            <div>
                                <div class="text-[13px] text-neutral-500 font-mono mb-3">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ $area->province }}</div>
                                <h2 class="m-0 text-[28px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50"><a href="{{ route('service-areas.show', $area) }}" class="transition-opacity hover:opacity-70">{{ $area->name }}</a></h2>
                            </div>

                            {{-- The long description lives on the area page only, so the same text is not published twice. --}}
                            <div>
                                @if($area->summary)
                                    <p class="m-0 text-[17px] leading-relaxed text-neutral-950 dark:text-neutral-50">{{ $area->summary }}</p>
                                @endif

                                @if(filled($area->sectors))
                                    <div class="mt-6">
                                        <x-eyebrow size="sm" class="mb-3">Kimlerle çalışıyorum</x-eyebrow>
                                        <ul class="m-0 p-0 list-none flex flex-wrap gap-2">
                                            @foreach($area->sectors as $sector)
                                                <li class="px-3 py-1.5 text-[13px] rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-700 dark:text-neutral-300">{{ $sector }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <a href="{{ route('service-areas.show', $area) }}" class="mt-6 inline-flex items-center gap-1.5 text-[13px] font-medium text-neutral-950 dark:text-neutral-50 transition-opacity hover:opacity-70">{{ $area->name }} web tasarım ve yazılım <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <x-local-working :provinces="$provinces" class="mt-16" />

                <div class="mt-14 flex flex-wrap items-center gap-x-6 gap-y-3">
                    <span class="text-[15px] text-neutral-600 dark:text-neutral-400">Bölgen listede yok mu? Uzaktan da çalışıyorum.</span>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">Bana yaz <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>

                <a href="{{ route('services') }}" class="mt-10 flex items-center justify-between gap-6 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-300 dark:hover:border-neutral-700 group">
                    <span>
                        <span class="block text-[17px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Ne tür işler yapıyorum?</span>
                        <span class="block mt-1 text-sm text-neutral-600 dark:text-neutral-400">Kapsam, süre ve fiyatlandırmasıyla sunduğum hizmetlere göz at.</span>
                    </span>
                    <span class="shrink-0 inline-flex items-center gap-1.5 text-[13px] font-medium text-neutral-950 dark:text-neutral-50">Hizmetler <i data-lucide="arrow-right" class="w-4 h-4 transition-transform group-hover:translate-x-0.5"></i></span>
                </a>
            @endif
        </div>
    </section>
@endsection
