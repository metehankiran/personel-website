@extends('layouts.app')

@section('title', 'Hizmetler')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Hizmetler</div>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Birlikte çalışmanın yolları.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">Üç farklı şekilde çalışıyorum. Hepsinde de açık iletişim, gerçekçi tahminler ve teslim sonrası destek dahil.</p>

            {{-- Service Cards --}}
            <div class="flex flex-wrap gap-5 justify-center mt-14 mb-20">
                @foreach($services as $service)
                    <div class="flex-[0_1_calc(33.333%-14px)] min-w-[280px] max-sm:flex-[0_1_100%] p-8 rounded-2xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 flex flex-col">
                        <div class="text-[11px] opacity-60 tracking-[1.4px] uppercase mb-3 text-neutral-950 dark:text-neutral-50">{{ $service->badge ?? 'Hizmet' }}</div>
                        <h3 class="m-0 mb-3 text-[26px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $service->title }}</h3>
                        <p class="m-0 mb-6 text-sm opacity-75 leading-relaxed text-neutral-950 dark:text-neutral-50">{{ $service->description }}</p>

                        <div class="flex flex-col gap-2.5 mb-7">
                            @foreach($service->features as $feature)
                                <div class="text-[13px] flex gap-2.5 items-baseline text-neutral-950 dark:text-neutral-50">
                                    <span class="opacity-40">—</span>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-auto pt-5 border-t border-neutral-200 dark:border-neutral-800">
                            <div class="flex justify-between items-baseline mb-4">
                                <div>
                                    <div class="text-[11px] opacity-60 tracking-[1.4px] uppercase mb-1">Fiyatlandırma</div>
                                    <div class="text-lg font-semibold text-neutral-950 dark:text-neutral-50">{{ $service->pricing }}</div>
                                </div>
                                @if($service->duration)
                                    <div class="text-right">
                                        <div class="text-[11px] opacity-60 tracking-[1.4px] uppercase mb-1">Süre</div>
                                        <div class="text-lg font-semibold text-neutral-950 dark:text-neutral-50">{{ $service->duration }}</div>
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('contact', ['service' => $service->id]) }}" data-contact-service="{{ $service->id }}"
                                x-data x-on:click.prevent="$dispatch('open-contact-modal', { subject: '{{ $service->id }}' })"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">Konuşalım →</a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Process Steps --}}
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-7">Nasıl çalışırız</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
                    <div class="text-[13px] text-neutral-500 font-mono mb-3">01</div>
                    <h4 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Tanışma</h4>
                    <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">Kısa bir görüşmede projeyi anlarım, sana içeride uygun olup olmadığımı söylerim.</p>
                </div>
                <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
                    <div class="text-[13px] text-neutral-500 font-mono mb-3">02</div>
                    <h4 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Teklif</h4>
                    <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">Net kapsam, tahmin ve fiyat. Her şey yazılı, sürpriz yok.</p>
                </div>
                <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
                    <div class="text-[13px] text-neutral-500 font-mono mb-3">03</div>
                    <h4 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">İnşa</h4>
                    <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">Haftalık demo + gerçek zamanlı staging. Süreç boyunca açık kanalım.</p>
                </div>
                <div class="border-t border-neutral-200 dark:border-neutral-800 pt-5">
                    <div class="text-[13px] text-neutral-500 font-mono mb-3">04</div>
                    <h4 class="m-0 mb-2 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">Teslim</h4>
                    <p class="m-0 text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">Production deployment, dokümantasyon, 30 gün ücretsiz destek.</p>
                </div>
            </div>
        </div>
    </section>

    <x-contact-modal />
@endsection
