@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp
<header class="site-header" role="banner">
    <div class="site-header-inner">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark"></span>
            <span class="brand-text">
                <span class="brand-name">Metehan Kıran</span>
                <span class="brand-role">full-stack developer</span>
            </span>
        </a>
        <nav class="nav" aria-label="Ana menü">
            <div class="nav-item">
                <a href="{{ route('home') }}" @class(['nav-link', 'active' => request()->routeIs('home')])>Ana sayfa</a>
            </div>
            <div class="nav-item">
                <a href="{{ $link('about') }}" @class(['nav-link', 'active' => request()->routeIs('about')])>Hakkımda</a>
            </div>
            <div class="nav-item">
                <button class="nav-link" type="button">İşler<svg width="9" height="9" viewBox="0 0 9 9" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2 3.5L4.5 6L7 3.5"/></svg></button>
                <div class="nav-dropdown">
                    <a class="nav-drop-link" href="{{ $link('projects') }}">
                        <div class="nav-drop-title">Projeler</div>
                        <div class="nav-drop-desc">28 case study</div>
                    </a>
                    <a class="nav-drop-link" href="{{ $link('services') }}">
                        <div class="nav-drop-title">Hizmetler</div>
                        <div class="nav-drop-desc">Freelance teklif</div>
                    </a>
                    <a class="nav-drop-link" href="{{ $link('references') }}">
                        <div class="nav-drop-title">Referanslar</div>
                        <div class="nav-drop-desc">Müşteri yorumları</div>
                    </a>
                    <a class="nav-drop-link" href="{{ $link('stack') }}">
                        <div class="nav-drop-title">Stack</div>
                        <div class="nav-drop-desc">Kullandığım teknolojiler</div>
                    </a>
                </div>
            </div>
            <div class="nav-item">
                <button class="nav-link" type="button">Yazı<svg width="9" height="9" viewBox="0 0 9 9" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"><path d="M2 3.5L4.5 6L7 3.5"/></svg></button>
                <div class="nav-dropdown">
                    <a class="nav-drop-link" href="{{ $link('blog') }}">
                        <div class="nav-drop-title">Blog</div>
                        <div class="nav-drop-desc">Teknik makaleler</div>
                    </a>
                    <a class="nav-drop-link" href="{{ $link('notes') }}">
                        <div class="nav-drop-title">Notlar</div>
                        <div class="nav-drop-desc">Kısa düşünceler</div>
                    </a>
                </div>
            </div>
            <div class="nav-item">
                <a href="{{ $link('cv') }}" @class(['nav-link', 'active' => request()->routeIs('cv')])>CV</a>
            </div>
            <div class="nav-item">
                <a href="{{ $link('contact') }}" @class(['nav-link', 'active' => request()->routeIs('contact')])>İletişim</a>
            </div>
        </nav>
        <div class="nav-right">
            <button type="button" class="nav-theme-toggle" data-theme-toggle aria-label="Tema değiştir" title="Tema değiştir">☾</button>
            <button type="button" class="search-trigger" data-search-trigger aria-label="Sitede ara (Cmd+K)" aria-haspopup="dialog">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" aria-hidden="true">
                    <circle cx="6" cy="6" r="4.2"/><path d="M9 9L12.5 12.5"/>
                </svg>
                <span class="search-trigger-text">Ara…</span>
                <kbd>⌘K</kbd>
            </button>
            <span class="status-dot">Müsait</span>
            <a href="{{ $link('contact') }}" class="btn btn-primary btn-sm">İletişim</a>
        </div>
    </div>
</header>
