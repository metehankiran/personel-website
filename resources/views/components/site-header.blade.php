@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';

    $navLinkClass = 'px-3.5 py-2 rounded-lg text-[13px] text-neutral-600 dark:text-neutral-400 flex items-center gap-1.5 whitespace-nowrap transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-900 hover:text-neutral-950 dark:hover:text-neutral-50';
    $navLinkActive = 'text-neutral-950 dark:text-neutral-50 font-medium';

    $dropLinkClass = 'block px-3 py-2.5 rounded-lg transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-900';
    $dropTitleClass = 'text-[13px] font-medium text-neutral-950 dark:text-neutral-50 mb-0.5';
    $dropDescClass = 'text-xs text-neutral-600 dark:text-neutral-400';

    $mobileNavClass = 'px-3 py-2.5 rounded-lg text-sm text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-900 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors';
    $mobileNavActive = 'text-neutral-950 dark:text-neutral-50 font-medium bg-neutral-100 dark:bg-neutral-900';
@endphp

<header class="sticky top-0 z-40 bg-white/[0.93] dark:bg-neutral-950/[0.93] backdrop-blur-md border-b border-neutral-200 dark:border-neutral-800" role="banner">
    <div class="max-w-7xl mx-auto px-6 xl:px-12 py-4 xl:py-5 flex items-center justify-between gap-4 xl:gap-8">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
            @if(\App\Support\Images::exists($general->logo_path))
                <img data-site-logo src="{{ \App\Support\Images::url($general->logo_path) }}" alt="" class="h-7 w-auto max-w-[120px] object-contain shrink-0" />
            @else
                <span class="w-7 h-7 rounded-full bg-neutral-950 dark:bg-neutral-50 relative inline-block shrink-0">
                    <span class="absolute inset-[5px] bg-white dark:bg-neutral-950 rounded-[9px]"></span>
                </span>
            @endif
            <span class="flex flex-col leading-tight whitespace-nowrap">
                <span class="text-sm font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</span>
                <span class="text-[11px] text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $general->author_title ?? 'developer' }}</span>
            </span>
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden xl:flex gap-1 items-center" aria-label="Ana menü">
            <div>
                <a href="{{ route('home') }}" @class([$navLinkClass, $navLinkActive => request()->routeIs('home')])><i data-lucide="home" class="w-3.5 h-3.5"></i> Ana sayfa</a>
            </div>
            <div>
                <a href="{{ $link('about') }}" @class([$navLinkClass, $navLinkActive => request()->routeIs('about')])><i data-lucide="user" class="w-3.5 h-3.5"></i> Hakkımda</a>
            </div>

            {{-- İşler Dropdown --}}
            <div class="relative group">
                <button @class([$navLinkClass, 'group-hover:bg-neutral-100 dark:group-hover:bg-neutral-900 group-hover:text-neutral-950 dark:group-hover:text-neutral-50']) type="button">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> İşler
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute top-full left-0 pt-1.5 hidden group-hover:block z-50">
                    <div class="min-w-[240px] bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl shadow-lg p-2">
                        <a class="{{ $dropLinkClass }}" href="{{ $link('projects') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="folder-open" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Projeler</div>
                            <div class="{{ $dropDescClass }}">Case study</div>
                        </a>
                        <a class="{{ $dropLinkClass }}" href="{{ $link('services') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="handshake" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Hizmetler</div>
                            <div class="{{ $dropDescClass }}">Freelance teklif</div>
                        </a>
                        <a class="{{ $dropLinkClass }}" href="{{ $link('references') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="quote" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Referanslar</div>
                            <div class="{{ $dropDescClass }}">Müşteri yorumları</div>
                        </a>
                        <a class="{{ $dropLinkClass }}" href="{{ $link('stack') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="layers" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Stack</div>
                            <div class="{{ $dropDescClass }}">Kullandığım teknolojiler</div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Yazı Dropdown --}}
            <div class="relative group">
                <button @class([$navLinkClass, 'group-hover:bg-neutral-100 dark:group-hover:bg-neutral-900 group-hover:text-neutral-950 dark:group-hover:text-neutral-50']) type="button">
                    <i data-lucide="pen-line" class="w-3.5 h-3.5"></i> Yazı
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute top-full left-0 pt-1.5 hidden group-hover:block z-50">
                    <div class="min-w-[240px] bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl shadow-lg p-2">
                        <a class="{{ $dropLinkClass }}" href="{{ $link('blog') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="notebook-pen" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Blog</div>
                            <div class="{{ $dropDescClass }}">Teknik makaleler</div>
                        </a>
                        <a class="{{ $dropLinkClass }}" href="{{ $link('bookmarks') }}">
                            <div class="{{ $dropTitleClass }}"><i data-lucide="bookmark" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Yer İşaretlerim</div>
                            <div class="{{ $dropDescClass }}">Faydalı linkler</div>
                        </a>
                    </div>
                </div>
            </div>

            <div>
                <a href="{{ $link('cv') }}" @class([$navLinkClass, $navLinkActive => request()->routeIs('cv')])><i data-lucide="file-text" class="w-3.5 h-3.5"></i> CV</a>
            </div>
        </nav>

        {{-- Right Section --}}
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
            {{-- Theme Toggle (radio pill) --}}
            <div class="inline-flex items-center rounded-lg border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 p-0.5 shrink-0" role="radiogroup" aria-label="Tema">
                <button type="button" data-theme-set="light"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Açık tema" title="Açık tema">
                    <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" data-theme-set="system"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Sistem teması" title="Sistem teması">
                    <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" data-theme-set="dark"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Koyu tema" title="Koyu tema">
                    <i data-lucide="moon" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            {{-- Search Trigger (Desktop) --}}
            <button type="button" class="search-trigger hidden sm:inline-flex items-center gap-2.5 px-3 py-[7px] min-w-[220px] bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg text-neutral-600 dark:text-neutral-400 text-[13px] font-sans transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 cursor-pointer"
                data-search-trigger aria-label="Sitede ara (Cmd+K)" aria-haspopup="dialog">
                <i data-lucide="search" class="w-3.5 h-3.5 opacity-60 shrink-0"></i>
                <span class="flex-1 text-left">Ara…</span>
                <kbd class="text-[11px] px-1.5 py-0.5 rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700 font-sans">⌘K</kbd>
            </button>

            {{-- Search Trigger (Mobile) --}}
            <button type="button" class="search-trigger sm:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 cursor-pointer"
                data-search-trigger aria-label="Sitede ara (Cmd+K)" aria-haspopup="dialog">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>

            {{-- Contact CTA (Desktop) --}}
            <a href="{{ $link('contact') }}" class="hidden md:inline-flex items-center gap-1.5 px-4 py-2 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">
                <i data-lucide="mail" class="w-3.5 h-3.5"></i> İletişim
            </a>

            {{-- Hamburger (Mobile) --}}
            <button type="button" id="mobile-menu-toggle"
                class="xl:hidden inline-flex items-center justify-center w-9 h-9 rounded-lg border border-neutral-200 dark:border-neutral-800 text-neutral-950 dark:text-neutral-50 transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-900 cursor-pointer"
                aria-label="Menü" aria-expanded="false" aria-controls="mobile-menu">
                <i data-lucide="menu" class="w-5 h-5" id="menu-icon-open"></i>
                <i data-lucide="x" class="w-5 h-5 hidden" id="menu-icon-close"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="xl:hidden max-h-0 overflow-hidden transition-all duration-300 ease-in-out border-t border-transparent">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex flex-col gap-1" aria-label="Mobil menü">
            <a href="{{ route('home') }}" @class([$mobileNavClass, $mobileNavActive => request()->routeIs('home')])>
                <span class="flex items-center gap-2"><i data-lucide="home" class="w-4 h-4"></i> Ana sayfa</span>
            </a>
            <a href="{{ $link('about') }}" @class([$mobileNavClass, $mobileNavActive => request()->routeIs('about')])>
                <span class="flex items-center gap-2"><i data-lucide="user" class="w-4 h-4"></i> Hakkımda</span>
            </a>

            {{-- İşler (Collapsible) --}}
            <div>
                <button type="button" class="w-full {{ $mobileNavClass }} flex items-center justify-between" data-mobile-collapse>
                    <span class="flex items-center gap-2"><i data-lucide="briefcase" class="w-4 h-4"></i> İşler</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-60"></i>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-200 ease-in-out pl-6">
                    <div class="flex flex-col gap-1 pt-1">
                        <a href="{{ $link('projects') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="folder-open" class="w-4 h-4"></i> Projeler</span></a>
                        <a href="{{ $link('services') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="handshake" class="w-4 h-4"></i> Hizmetler</span></a>
                        <a href="{{ $link('references') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="quote" class="w-4 h-4"></i> Referanslar</span></a>
                        <a href="{{ $link('stack') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="layers" class="w-4 h-4"></i> Stack</span></a>
                    </div>
                </div>
            </div>

            {{-- Yazı (Collapsible) --}}
            <div>
                <button type="button" class="w-full {{ $mobileNavClass }} flex items-center justify-between" data-mobile-collapse>
                    <span class="flex items-center gap-2"><i data-lucide="pen-line" class="w-4 h-4"></i> Yazı</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-60"></i>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-200 ease-in-out pl-6">
                    <div class="flex flex-col gap-1 pt-1">
                        <a href="{{ $link('blog') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="notebook-pen" class="w-4 h-4"></i> Blog</span></a>
                        <a href="{{ $link('bookmarks') }}" class="{{ $mobileNavClass }}"><span class="flex items-center gap-2"><i data-lucide="bookmark" class="w-4 h-4"></i> Yer İşaretlerim</span></a>
                    </div>
                </div>
            </div>

            <a href="{{ $link('cv') }}" @class([$mobileNavClass, $mobileNavActive => request()->routeIs('cv')])>
                <span class="flex items-center gap-2"><i data-lucide="file-text" class="w-4 h-4"></i> CV</span>
            </a>
            <a href="{{ $link('contact') }}" @class([$mobileNavClass, $mobileNavActive => request()->routeIs('contact')])>
                <span class="flex items-center gap-2"><i data-lucide="mail" class="w-4 h-4"></i> İletişim</span>
            </a>

            {{-- Mobile CTA --}}
            <a href="{{ $link('contact') }}" class="mt-3 flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">
                <i data-lucide="send" class="w-4 h-4"></i> İletişim
            </a>
        </nav>
    </div>
</header>

@push('scripts')
<script>
(function() {
    // Mobile menu toggle
    var toggle = document.getElementById('mobile-menu-toggle');
    var menu = document.getElementById('mobile-menu');
    var openIcon = document.getElementById('menu-icon-open');
    var closeIcon = document.getElementById('menu-icon-close');

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            var isOpen = menu.style.maxHeight && menu.style.maxHeight !== '0px';
            if (isOpen) {
                menu.style.maxHeight = '0px';
                menu.style.borderColor = 'transparent';
                openIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
                toggle.setAttribute('aria-expanded', 'false');
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
                menu.style.borderColor = '';
                menu.classList.remove('border-transparent');
                menu.classList.add('border-neutral-200', 'dark:border-neutral-800');
                openIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
                toggle.setAttribute('aria-expanded', 'true');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        });
    }

    // Collapsible sub-menus
    document.querySelectorAll('[data-mobile-collapse]').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var panel = btn.nextElementSibling;
            var isOpen = panel.style.maxHeight && panel.style.maxHeight !== '0px';
            var delta = panel.scrollHeight;

            if (isOpen) {
                panel.style.maxHeight = '0px';
                if (menu) menu.style.maxHeight = (menu.scrollHeight - delta) + 'px';
            } else {
                panel.style.maxHeight = delta + 'px';
                if (menu) menu.style.maxHeight = (menu.scrollHeight + delta) + 'px';
            }
        });
    });
})();
</script>
@endpush
