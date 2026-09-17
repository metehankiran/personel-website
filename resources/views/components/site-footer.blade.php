@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
    $footerLinkClass = 'text-[13px] text-neutral-950 dark:text-neutral-50 opacity-85 hover:opacity-100 transition-opacity';
@endphp

<footer class="border-t border-neutral-200 dark:border-neutral-800 mt-10" role="contentinfo">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-12 lg:pt-[60px] pb-8 lg:pb-10 grid grid-cols-3 lg:grid-cols-[1.5fr_1fr_1fr_1fr] gap-x-4 gap-y-8 sm:gap-8 lg:gap-12">

        {{-- Brand Column --}}
        <div class="col-span-3 lg:col-span-1">
            <div class="flex items-center gap-2.5 mb-4">
                <x-brand-mark logo class="w-6 h-6" logo-class="h-6 max-w-[110px]" />
                <span class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</span>
            </div>
            @if($general->footer_text)
                <p class="text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-xs m-0">{{ $general->footer_text }}</p>
            @endif
        </div>

        {{-- Site Links --}}
        <div>
            <x-eyebrow size="sm" class="mb-3.5">Site</x-eyebrow>
            <div class="flex flex-col gap-2.5">
                <a href="{{ route('home') }}" class="{{ $footerLinkClass }}">Ana sayfa</a>
                <a href="{{ $link('about') }}" class="{{ $footerLinkClass }}">Hakkımda</a>
                <a href="{{ $link('projects') }}" class="{{ $footerLinkClass }}">Projeler</a>
                <a href="{{ $link('blog') }}" class="{{ $footerLinkClass }}">Blog</a>
            </div>
        </div>

        {{-- İşler Links --}}
        <div>
            <x-eyebrow size="sm" class="mb-3.5">İşler</x-eyebrow>
            <div class="flex flex-col gap-2.5">
                <a href="{{ $link('services') }}" class="{{ $footerLinkClass }}">Hizmetler</a>
                <a href="{{ $link('stack') }}" class="{{ $footerLinkClass }}">Teknolojiler</a>
                <a href="{{ $link('references') }}" class="{{ $footerLinkClass }}">Referanslar</a>
                <a href="{{ $link('cv') }}" class="{{ $footerLinkClass }}">CV</a>
            </div>
        </div>

        {{-- Social Links --}}
        @if(filled($general->author_email) || $social->profiles() !== [])
        <div>
            <x-eyebrow size="sm" class="mb-3.5">Bağlan</x-eyebrow>
            <div class="flex flex-col gap-2.5">
                @if($general->author_email)
                    <a href="mailto:{{ $general->author_email }}" class="{{ $footerLinkClass }} inline-flex items-center gap-1.5">
                        <i data-lucide="mail" class="w-3.5 h-3.5"></i> Email
                    </a>
                @endif
                @foreach($social->profiles() as $platform => $profile)
                    <a href="{{ $profile['url'] }}" target="_blank" rel="noopener" class="{{ $footerLinkClass }} inline-flex items-center gap-1.5">
                        <x-social-icon :platform="$platform" class="w-3.5 h-3.5" /> {{ $profile['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Footer Bottom --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-6 border-t border-neutral-200 dark:border-neutral-800 flex flex-row justify-between gap-2 text-xs text-neutral-500">
        <span>© {{ now()->year }} {{ $general->author_name }}</span>
        <span>{{ $general->author_location ?? '' }}</span>
    </div>
</footer>
