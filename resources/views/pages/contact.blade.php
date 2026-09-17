@extends('layouts.app')

@section('title', 'İletişim')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-start">

                {{-- Left Column — Contact Info --}}
                <div>
                    <x-eyebrow class="mb-3">İletişim</x-eyebrow>
                    <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Birlikte<br/>bir şey yapalım.</h1>
                    <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[480px]">Bir proje fikrin mi var? Sadece selam mı vereceksin? Her ikisi de iyi. Genelde 24 saat içinde dönüyorum.</p>

                    <div class="mt-12">
                        @if($general->author_email)
                            <a href="mailto:{{ $general->author_email }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group">
                                <div>
                                    <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">Email</div>
                                    <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ $general->author_email }}</div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="mail" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @if($general->author_phone)
                            <a href="tel:{{ $general->author_phone }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group">
                                <div>
                                    <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">Telefon</div>
                                    <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ $general->author_phone }}</div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="phone" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @foreach($social->profiles() as $platform => $profile)
                            <a href="{{ $profile['url'] }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div class="flex items-center gap-3">
                                    <x-social-icon :platform="$platform" class="w-5 h-5 text-neutral-400 dark:text-neutral-600" />
                                    <div>
                                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">{{ $profile['label'] }}</div>
                                        <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ Str::of($profile['url'])->after('://')->rtrim('/') }}</div>
                                    </div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endforeach

                        @php($addressLine = $general->author_address ?: $general->author_location)
                        @if($addressLine)
                            <{{ $general->google_maps_url ? 'a' : 'div' }}
                                @if($general->google_maps_url) href="{{ $general->google_maps_url }}" target="_blank" rel="noopener" @endif
                                @class(['py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center gap-4', 'transition-all hover:pl-1 group' => $general->google_maps_url])>
                                <div>
                                    <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">{{ $general->google_maps_url ? 'Adres · Haritada aç' : 'Adres' }}</div>
                                    <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ $addressLine }}</div>
                                </div>
                                <span class="shrink-0 text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </span>
                            </{{ $general->google_maps_url ? 'a' : 'div' }}>
                        @endif
                    </div>
                </div>

                {{-- Right Column — Contact Form --}}
                <livewire:contact-form :service="request()->query('service')" />
            </div>
        </div>
    </section>
@endsection
