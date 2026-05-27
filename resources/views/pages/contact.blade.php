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
                <form class="p-6 lg:p-10 border border-neutral-200 dark:border-neutral-800 rounded-2xl bg-neutral-50 dark:bg-neutral-900 flex flex-col gap-5 sticky top-24" action="{{ route('contact.send') }}" method="POST">
                    @csrf
                    <h3 class="text-[22px] font-semibold text-neutral-950 dark:text-neutral-50 m-0">Hızlıca yaz</h3>

                    @if(session('success'))
                        <div class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div>
                        <label for="contact-name" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Adın</label>
                        <input id="contact-name" type="text" name="name"
                            class="w-full px-3.5 py-2.5 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
                            placeholder="Adınız" value="{{ old('name') }}" required />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact-email" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Email</label>
                        <input id="contact-email" type="email" name="email"
                            class="w-full px-3.5 py-2.5 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
                            placeholder="siz@example.com" value="{{ old('email') }}" required />
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact-phone" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Telefon</label>
                        <input id="contact-phone" type="tel" name="phone"
                            class="w-full px-3.5 py-2.5 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
                            placeholder="+90 5XX XXX XX XX" value="{{ old('phone') }}" />
                        @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact-subject" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Konu</label>
                        <div class="relative">
                        <select id="contact-subject" name="subject"
                            class="w-full appearance-none px-3.5 py-2.5 pr-9 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50"
                            required>
                            <option value="">Seçiniz</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->value }}" {{ old('subject') === $subject->value ? 'selected' : '' }}>{{ $subject->getLabel() }}</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none"></i>
                        </div>
                        @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="contact-message" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Mesaj</label>
                        <textarea id="contact-message" name="message" rows="5"
                            class="w-full px-3.5 py-2.5 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 resize-y placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
                            placeholder="Projeyi anlatabilir misin..." required>{{ old('message') }}</textarea>
                        @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($general->kvkk_page_slug)
                        <div>
                            <label class="flex items-start gap-2 text-[13px] text-neutral-600 dark:text-neutral-400 cursor-pointer">
                                <input type="checkbox" name="kvkk_consent" value="1" required class="mt-0.5 accent-neutral-950 dark:accent-neutral-50" />
                                <span><a href="{{ route('pages.show', $general->kvkk_page_slug) }}" target="_blank" class="underline underline-offset-2 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum.</span>
                            </label>
                            @error('kvkk_consent') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 cursor-pointer border-none font-sans">
                        Gönder <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
