@extends('layouts.app')

@section('title', 'İletişim — ' . $general->site_title)

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-start">

                {{-- Left Column — Contact Info --}}
                <div>
                    <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">İletişim</div>
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

                        @if($social->github_url)
                            <a href="{{ $social->github_url }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div class="flex items-center gap-3">
                                    <x-social-icon platform="github" class="w-5 h-5 text-neutral-400 dark:text-neutral-600" />
                                    <div>
                                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">GitHub</div>
                                        <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ str_replace('https://', '', $social->github_url) }}</div>
                                    </div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @if($social->linkedin_url)
                            <a href="{{ $social->linkedin_url }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div class="flex items-center gap-3">
                                    <x-social-icon platform="linkedin" class="w-5 h-5 text-neutral-400 dark:text-neutral-600" />
                                    <div>
                                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">LinkedIn</div>
                                        <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ str_replace('https://', '', $social->linkedin_url) }}</div>
                                    </div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @if($social->twitter_url)
                            <a href="{{ $social->twitter_url }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div class="flex items-center gap-3">
                                    <x-social-icon platform="twitter" class="w-5 h-5 text-neutral-400 dark:text-neutral-600" />
                                    <div>
                                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">Twitter / X</div>
                                        <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ str_replace('https://', '', $social->twitter_url) }}</div>
                                    </div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @if($social->instagram_url)
                            <a href="{{ $social->instagram_url }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div class="flex items-center gap-3">
                                    <x-social-icon platform="instagram" class="w-5 h-5 text-neutral-400 dark:text-neutral-600" />
                                    <div>
                                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">Instagram</div>
                                        <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ str_replace('https://', '', $social->instagram_url) }}</div>
                                    </div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="arrow-up-right" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif

                        @if($general->google_maps_url)
                            <a href="{{ $general->google_maps_url }}" class="py-5 border-b border-neutral-200 dark:border-neutral-800 flex justify-between items-center transition-all hover:pl-1 group" target="_blank" rel="noopener">
                                <div>
                                    <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase mb-1">Harita</div>
                                    <div class="text-base font-medium text-neutral-950 dark:text-neutral-50">{{ $general->author_location ?? 'Konum' }}</div>
                                </div>
                                <span class="text-neutral-400 dark:text-neutral-600 group-hover:text-neutral-600 dark:group-hover:text-neutral-400 transition-colors">
                                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                                </span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Right Column — Contact Form --}}
                <livewire:contact-form :service="request()->query('service')" />
            </div>
        </div>
    </section>
@endsection
