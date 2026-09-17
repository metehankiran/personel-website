@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';

    $navLinkClass = 'px-3.5 py-2 rounded-lg text-[13px] text-neutral-600 dark:text-neutral-400 flex items-center gap-1.5 whitespace-nowrap transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-900 hover:text-neutral-950 dark:hover:text-neutral-50';
    $navLinkActive = 'text-neutral-950 dark:text-neutral-50 font-medium';

    $dropLinkClass = 'block px-3 py-2.5 rounded-lg transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-900';
    $dropTitleClass = 'text-[13px] font-medium text-neutral-950 dark:text-neutral-50 mb-0.5';
    $dropDescClass = 'text-xs text-neutral-600 dark:text-neutral-400';

    $mobileNavClass = 'px-3 py-2.5 rounded-lg text-sm text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-900 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors';
    $mobileNavActive = 'text-neutral-950 dark:text-neutral-50 font-medium bg-neutral-100 dark:bg-neutral-900';
    $dropLinkActive = 'bg-neutral-100 dark:bg-neutral-900';

    // Route patterns per menu item; "name.*" keeps an item active on its sub pages.
    $nav = collect([
        'home' => ['home'],
        'about' => ['about'],
        'projects' => ['projects', 'projects.*'],
        'services' => ['services'],
        'references' => ['references'],
        'stack' => ['stack'],
        'blog' => ['blog', 'blog.*'],
        'bookmarks' => ['bookmarks'],
        'cv' => ['cv'],
        'contact' => ['contact'],
    ])->map(fn (array $patterns): bool => request()->routeIs(...$patterns));

    $groups = [
        'works' => $nav->only(['projects', 'services', 'references', 'stack'])->contains(true),
        'writing' => $nav->only(['blog', 'bookmarks'])->contains(true),
        'pages' => request()->routeIs('pages.show', 'faq'),
    ];

    // Slug of the static page being viewed, to mark it inside the "Sayfalar" menu.
    $openPageSlug = request()->routeIs('pages.show') ? request()->route('page')?->slug : null;
    $onFaq = request()->routeIs('faq');

    $current = fn (string $item): string => $nav[$item] ? 'aria-current="page"' : '';
@endphp

<header class="sticky top-0 z-40 bg-white/[0.93] dark:bg-neutral-950/[0.93] backdrop-blur-md border-b border-neutral-200 dark:border-neutral-800" role="banner">
    <div class="max-w-7xl mx-auto px-6 xl:px-12 py-4 xl:py-5 flex items-center justify-between gap-4 xl:gap-8">

        {{-- Brand --}}
        <a href="{{ route('home') }}" data-site-brand class="flex items-center gap-3 min-w-0 xl:shrink-0">
            <x-brand-mark logo class="w-7 h-7" logo-class="h-7 max-w-[120px]" />
            <span class="flex flex-col leading-tight min-w-0">
                <span data-site-brand-name class="truncate text-sm font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</span>
                <span class="truncate text-[11px] text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $general->author_title ?? 'developer' }}</span>
            </span>
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden xl:flex gap-1 items-center" aria-label="Ana menü">
            <div>
                <a href="{{ route('home') }}" {!! $current('home') !!} @class([$navLinkClass, $navLinkActive => $nav['home']])><i data-lucide="home" class="w-3.5 h-3.5"></i> Ana sayfa</a>
            </div>
            <div>
                <a href="{{ $link('about') }}" {!! $current('about') !!} @class([$navLinkClass, $navLinkActive => $nav['about']])><i data-lucide="user" class="w-3.5 h-3.5"></i> Hakkımda</a>
            </div>

            {{-- İşler Dropdown --}}
            <div class="relative group">
                <button data-nav-group="works" data-active="{{ $groups['works'] ? 'true' : 'false' }}" @class([$navLinkClass, $navLinkActive => $groups['works'], 'group-hover:bg-neutral-100 dark:group-hover:bg-neutral-900 group-hover:text-neutral-950 dark:group-hover:text-neutral-50']) type="button">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> İşler
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute top-full left-0 pt-1.5 hidden group-hover:block z-50">
                    <div class="min-w-[240px] bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl shadow-lg p-2">
                        <a href="{{ $link('projects') }}" {!! $current('projects') !!} @class([$dropLinkClass, $dropLinkActive => $nav['projects']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="folder-open" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Projeler</div>
                            <div class="{{ $dropDescClass }}">Case study</div>
                        </a>
                        <a href="{{ $link('services') }}" {!! $current('services') !!} @class([$dropLinkClass, $dropLinkActive => $nav['services']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="handshake" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Hizmetler</div>
                            <div class="{{ $dropDescClass }}">Freelance teklif</div>
                        </a>
                        <a href="{{ $link('references') }}" {!! $current('references') !!} @class([$dropLinkClass, $dropLinkActive => $nav['references']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="quote" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Referanslar</div>
                            <div class="{{ $dropDescClass }}">Müşteri yorumları</div>
                        </a>
                        <a href="{{ $link('stack') }}" {!! $current('stack') !!} @class([$dropLinkClass, $dropLinkActive => $nav['stack']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="layers" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Teknolojiler</div>
                            <div class="{{ $dropDescClass }}">Araçlar ve deneyim sürem</div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Yazı Dropdown --}}
            <div class="relative group">
                <button data-nav-group="writing" data-active="{{ $groups['writing'] ? 'true' : 'false' }}" @class([$navLinkClass, $navLinkActive => $groups['writing'], 'group-hover:bg-neutral-100 dark:group-hover:bg-neutral-900 group-hover:text-neutral-950 dark:group-hover:text-neutral-50']) type="button">
                    <i data-lucide="pen-line" class="w-3.5 h-3.5"></i> Yazı
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform group-hover:rotate-180"></i>
                </button>
                <div class="absolute top-full left-0 pt-1.5 hidden group-hover:block z-50">
                    <div class="min-w-[240px] bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl shadow-lg p-2">
                        <a href="{{ $link('blog') }}" {!! $current('blog') !!} @class([$dropLinkClass, $dropLinkActive => $nav['blog']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="notebook-pen" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Blog</div>
                            <div class="{{ $dropDescClass }}">Teknik makaleler</div>
                        </a>
                        <a href="{{ $link('bookmarks') }}" {!! $current('bookmarks') !!} @class([$dropLinkClass, $dropLinkActive => $nav['bookmarks']])>
                            <div class="{{ $dropTitleClass }}"><i data-lucide="bookmark" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Yer İşaretlerim</div>
                            <div class="{{ $dropDescClass }}">Faydalı linkler</div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Sayfalar Dropdown: published static pages, managed in the panel --}}
                <div class="relative group">
                    <button data-nav-group="pages" data-active="{{ $groups['pages'] ? 'true' : 'false' }}" @class([$navLinkClass, $navLinkActive => $groups['pages'], 'group-hover:bg-neutral-100 dark:group-hover:bg-neutral-900 group-hover:text-neutral-950 dark:group-hover:text-neutral-50']) type="button">
                        <i data-lucide="files" class="w-3.5 h-3.5"></i> Sayfalar
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 opacity-60 transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute top-full left-0 pt-1.5 hidden group-hover:block z-50">
                        <div class="min-w-[240px] bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 rounded-xl shadow-lg p-2">
                            <a href="{{ route('faq') }}" {!! $onFaq ? 'aria-current="page"' : '' !!} @class([$dropLinkClass, $dropLinkActive => $onFaq])>
                                <div class="{{ $dropTitleClass }} mb-0"><i data-lucide="circle-help" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>Sıkça Sorulan Sorular</div>
                            </a>
                            @foreach($menuPages as $menuPage)
                                <a href="{{ route('pages.show', $menuPage) }}" {!! $openPageSlug === $menuPage->slug ? 'aria-current="page"' : '' !!} @class([$dropLinkClass, $dropLinkActive => $openPageSlug === $menuPage->slug])>
                                    <div class="{{ $dropTitleClass }} mb-0"><i data-lucide="file-text" class="w-3.5 h-3.5 inline mr-1.5 opacity-50"></i>{{ $menuPage->title }}</div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

            <div>
                <a href="{{ $link('cv') }}" {!! $current('cv') !!} @class([$navLinkClass, $navLinkActive => $nav['cv']])><i data-lucide="file-text" class="w-3.5 h-3.5"></i> CV</a>
            </div>
        </nav>

        {{-- Right Section --}}
        <div class="flex items-center gap-2 xl:gap-3 shrink-0">
            {{-- Theme Toggle (radio pill) --}}
            <div class="inline-flex items-center rounded-lg border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 p-0.5 shrink-0" role="radiogroup" aria-label="Tema">
                <button type="button" data-theme-set="light" role="radio" aria-checked="false"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Açık tema" title="Açık tema">
                    <i data-lucide="sun" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" data-theme-set="system" role="radio" aria-checked="false"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Sistem teması" title="Sistem teması">
                    <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                </button>
                <button type="button" data-theme-set="dark" role="radio" aria-checked="false"
                    class="theme-radio w-8 h-8 rounded-md inline-flex items-center justify-center text-sm transition-all cursor-pointer"
                    aria-label="Koyu tema" title="Koyu tema">
                    <i data-lucide="moon" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            {{-- Search Trigger (Desktop) --}}
            <button type="button" class="search-trigger hidden sm:inline-flex items-center gap-2.5 px-3 py-[7px] min-w-[220px] xl:min-w-[150px] 2xl:min-w-[220px] bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-lg text-neutral-600 dark:text-neutral-400 text-[13px] font-sans transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 cursor-pointer"
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
            <a href="{{ route('home') }}" {!! $current('home') !!} @class([$mobileNavClass, $mobileNavActive => $nav['home']])>
                <span class="flex items-center gap-2"><i data-lucide="home" class="w-4 h-4"></i> Ana sayfa</span>
            </a>
            <a href="{{ $link('about') }}" {!! $current('about') !!} @class([$mobileNavClass, $mobileNavActive => $nav['about']])>
                <span class="flex items-center gap-2"><i data-lucide="user" class="w-4 h-4"></i> Hakkımda</span>
            </a>

            {{-- İşler (Collapsible) --}}
            <div>
                <button type="button" data-nav-group="works" data-active="{{ $groups['works'] ? 'true' : 'false' }}" @class(['w-full flex items-center justify-between', $mobileNavClass, 'text-neutral-950 dark:text-neutral-50 font-medium' => $groups['works']]) data-mobile-collapse>
                    <span class="flex items-center gap-2"><i data-lucide="briefcase" class="w-4 h-4"></i> İşler</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-60"></i>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-200 ease-in-out pl-6" @if($groups['works']) style="max-height: none" @endif>
                    <div class="flex flex-col gap-1 pt-1">
                        <a href="{{ $link('projects') }}" {!! $current('projects') !!} @class([$mobileNavClass, $mobileNavActive => $nav['projects']])><span class="flex items-center gap-2"><i data-lucide="folder-open" class="w-4 h-4"></i> Projeler</span></a>
                        <a href="{{ $link('services') }}" {!! $current('services') !!} @class([$mobileNavClass, $mobileNavActive => $nav['services']])><span class="flex items-center gap-2"><i data-lucide="handshake" class="w-4 h-4"></i> Hizmetler</span></a>
                        <a href="{{ $link('references') }}" {!! $current('references') !!} @class([$mobileNavClass, $mobileNavActive => $nav['references']])><span class="flex items-center gap-2"><i data-lucide="quote" class="w-4 h-4"></i> Referanslar</span></a>
                        <a href="{{ $link('stack') }}" {!! $current('stack') !!} @class([$mobileNavClass, $mobileNavActive => $nav['stack']])><span class="flex items-center gap-2"><i data-lucide="layers" class="w-4 h-4"></i> Teknolojiler</span></a>
                    </div>
                </div>
            </div>

            {{-- Yazı (Collapsible) --}}
            <div>
                <button type="button" data-nav-group="writing" data-active="{{ $groups['writing'] ? 'true' : 'false' }}" @class(['w-full flex items-center justify-between', $mobileNavClass, 'text-neutral-950 dark:text-neutral-50 font-medium' => $groups['writing']]) data-mobile-collapse>
                    <span class="flex items-center gap-2"><i data-lucide="pen-line" class="w-4 h-4"></i> Yazı</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-60"></i>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-200 ease-in-out pl-6" @if($groups['writing']) style="max-height: none" @endif>
                    <div class="flex flex-col gap-1 pt-1">
                        <a href="{{ $link('blog') }}" {!! $current('blog') !!} @class([$mobileNavClass, $mobileNavActive => $nav['blog']])><span class="flex items-center gap-2"><i data-lucide="notebook-pen" class="w-4 h-4"></i> Blog</span></a>
                        <a href="{{ $link('bookmarks') }}" {!! $current('bookmarks') !!} @class([$mobileNavClass, $mobileNavActive => $nav['bookmarks']])><span class="flex items-center gap-2"><i data-lucide="bookmark" class="w-4 h-4"></i> Yer İşaretlerim</span></a>
                    </div>
                </div>
            </div>

            {{-- Sayfalar (Collapsible) --}}
                <div>
                    <button type="button" data-nav-group="pages" data-active="{{ $groups['pages'] ? 'true' : 'false' }}" @class(['w-full flex items-center justify-between', $mobileNavClass, 'text-neutral-950 dark:text-neutral-50 font-medium' => $groups['pages']]) data-mobile-collapse>
                        <span class="flex items-center gap-2"><i data-lucide="files" class="w-4 h-4"></i> Sayfalar</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 opacity-60"></i>
                    </button>
                    <div class="max-h-0 overflow-hidden transition-all duration-200 ease-in-out pl-6" @if($groups['pages']) style="max-height: none" @endif>
                        <div class="flex flex-col gap-1 pt-1">
                            <a href="{{ route('faq') }}" {!! $onFaq ? 'aria-current="page"' : '' !!} @class([$mobileNavClass, $mobileNavActive => $onFaq])><span class="flex items-center gap-2"><i data-lucide="circle-help" class="w-4 h-4"></i> Sıkça Sorulan Sorular</span></a>
                            @foreach($menuPages as $menuPage)
                                <a href="{{ route('pages.show', $menuPage) }}" {!! $openPageSlug === $menuPage->slug ? 'aria-current="page"' : '' !!} @class([$mobileNavClass, $mobileNavActive => $openPageSlug === $menuPage->slug])><span class="flex items-center gap-2"><i data-lucide="file-text" class="w-4 h-4"></i> {{ $menuPage->title }}</span></a>
                            @endforeach
                        </div>
                    </div>
                </div>

            <a href="{{ $link('cv') }}" {!! $current('cv') !!} @class([$mobileNavClass, $mobileNavActive => $nav['cv']])>
                <span class="flex items-center gap-2"><i data-lucide="file-text" class="w-4 h-4"></i> CV</span>
            </a>
            <a href="{{ $link('contact') }}" {!! $current('contact') !!} @class([$mobileNavClass, $mobileNavActive => $nav['contact']])>
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

    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            // Look the icons up on every click: lucide replaces the <i> placeholders with <svg> after this script runs.
            var openIcon = document.getElementById('menu-icon-open');
            var closeIcon = document.getElementById('menu-icon-close');
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
