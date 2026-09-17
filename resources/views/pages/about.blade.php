@extends('layouts.app')

@section('title', 'Hakkımda')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-10 lg:gap-16 items-start">

                {{-- Main Content --}}
                <div>
                    <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Hakkımda</div>
                    <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">5 yıldır kod yazıyor, ürün teslim ediyorum.</h1>

                    <div class="flex flex-col gap-[18px] text-[17px] leading-[1.7] opacity-90 max-w-[640px] mt-8 text-neutral-950 dark:text-neutral-50 [&>p]:m-0 [&>em]:italic [&>em]:font-normal">
                        <p>{{ $general->author_location }}'da yaşıyorum. Lise yıllarında PHP ile başlayan kod yolculuğum, bugün Laravel, Vue.js ve .NET Core ekosistemlerinde derinleşmiş bir pratiğe dönüştü. 5 yıldır freelance olarak çalışıyorum.</p>
                        <p>E-ticaret altyapılarından kurumsal CRM'lere, dahili yönetim araçlarından mobil uygulama backend'lerine kadar geniş bir yelpazede proje teslim ettim. 40'tan fazla müşteriyle çalıştım — bazılarıyla hâlâ çalışmaya devam ediyorum.</p>
                        <p>İyi yazılım benim için <em>fark edilmeyen</em> yazılımdır: kullanıcı düşünmeden iş gören, bakımı kolay, gelecek versiyonlara dirençli kod. O yüzden modaya kapılmadan, doğrulanmış araçlarla çalışmayı tercih ediyorum.</p>
                        <p>Kodun dışında: kitap okumayı, uzun yürüyüşleri ve mekanik klavyeleri seviyorum.</p>
                    </div>

                    {{-- Timeline --}}
                    @if($timeline->isNotEmpty())
                        <div class="mt-16">
                            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-5">Zaman çizelgesi</div>

                            @foreach($timeline as $entry)
                                <div @class(['grid grid-cols-[80px_200px_1fr] max-sm:grid-cols-[70px_1fr] gap-x-6 py-5 border-b border-neutral-200 dark:border-neutral-800 items-baseline', 'border-t' => $loop->first])>
                                    <span class="text-[13px] text-neutral-500 tabular-nums">{{ $entry->period }}</span>
                                    <span class="text-[15px] font-semibold text-neutral-950 dark:text-neutral-50">{{ $entry->title }}</span>
                                    @if($entry->description)
                                        <span class="text-sm text-neutral-600 dark:text-neutral-400 max-sm:col-span-full">{{ $entry->description }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="flex flex-col gap-6 lg:sticky lg:top-24">
                    <div class="aspect-[4/5] bg-gradient-to-br from-neutral-200 dark:from-neutral-800 to-neutral-100 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-xl flex items-center justify-center text-neutral-400 dark:text-neutral-600 text-xs">[ portre fotoğrafı ]</div>

                    <div class="p-6 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-4">Hızlı bilgiler</div>
                        <div class="flex flex-col gap-3 text-[13px]">
                            @if($general->author_location)
                                <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                    <span class="text-neutral-500">Lokasyon</span>
                                    <span class="font-medium text-neutral-950 dark:text-neutral-50">{{ $general->author_location }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                <span class="text-neutral-500">Çalışma şekli</span>
                                <span class="font-medium text-neutral-950 dark:text-neutral-50">Uzaktan</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                <span class="text-neutral-500">Diller</span>
                                <span class="font-medium text-neutral-950 dark:text-neutral-50">TR · EN</span>
                            </div>
                            @if($general->homepage_stats)
                                @foreach(collect($general->homepage_stats)->whereIn('label', ['Tecrübe']) as $stat)
                                    <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                        <span class="text-neutral-500">{{ $stat['label'] }}</span>
                                        <span class="font-medium text-neutral-950 dark:text-neutral-50">{{ $stat['value'] }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a href="{{ $link('cv') }}" class="mt-5 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">
                            <i data-lucide="download" class="w-3.5 h-3.5"></i> CV'yi indir
                        </a>
                    </div>
                </aside>

            </div>
        </div>
    </section>
@endsection
