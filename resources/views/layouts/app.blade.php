@php
    // Pages declare only their own part of the title; the site title suffix is added here.
    $siteTitle = $general->site_title ?: config('app.name');
    // Sections arrive already escaped; decode them so the tags below escape exactly once.
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES);
    $fullTitle = html_entity_decode(trim($__env->yieldContent('full_title')), ENT_QUOTES) ?: ($pageTitle !== '' ? "{$pageTitle} — {$siteTitle}" : $siteTitle);
    $metaDescription = \App\Support\Seo::description(html_entity_decode($__env->yieldContent('meta_description'), ENT_QUOTES), $seo->meta_description, $general->site_description, $siteTitle);
    $canonicalUrl = \App\Support\Seo::canonical();
    $shareImage = \App\Support\Seo::absolute(trim($__env->yieldContent('og_image')) ?: \App\Support\Images::og($seo->og_image_path ?? null));
    $shareImageSize = \App\Support\Images::dimensionsFromUrl($shareImage);
@endphp
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta property="og:site_name" content="{{ $siteTitle }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">
@if($shareImageSize)
    <meta property="og:image:width" content="{{ $shareImageSize['width'] }}">
    <meta property="og:image:height" content="{{ $shareImageSize['height'] }}">
@endif
    <meta property="og:image:alt" content="{{ $fullTitle }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $fullTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $shareImage }}">
    @stack('meta')

    <link rel="icon" href="{{ \App\Support\Images::url($general->favicon_path ?? null, 'favicon') }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" type="application/rss+xml" title="{{ $siteTitle }} — Blog" href="{{ \App\Support\Seo::route('feed') }}">
    {{ \App\Support\Schema::script(\App\Support\Schema::website(), \App\Support\Schema::person()) }}
    @stack('schema')
    @if(filled($seo->google_search_console_id))
        <meta name="google-site-verification" content="{{ $seo->google_search_console_id }}">
    @endif

    <script>
        // Runs before the first paint: sets the resolved theme and the chosen mode, so neither
        // the page colours nor the theme toggle have to wait for the deferred scripts.
        (function () {
            var mode = null;
            try { mode = localStorage.getItem('mk-theme'); } catch (e) {}
            if (['light', 'dark', 'system'].indexOf(mode) === -1) { mode = 'system'; }
            var resolved = mode === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : mode;
            document.documentElement.setAttribute('data-theme-mode', mode);
            document.documentElement.setAttribute('data-theme', resolved);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="font-sans text-sm leading-normal tracking-tight bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 antialiased min-h-screen flex flex-col">
    <x-site-header />

    <main id="main-content" tabindex="-1" class="flex-1">
        @yield('content')
    </main>

    <x-site-footer />

    <script src="{{ \App\Support\Assets::versioned('theme/js/main.js') }}" defer></script>
    <script>window.mkSearchUrl = '{{ route("search.index") }}';</script>
    <script src="{{ \App\Support\Assets::versioned('theme/js/search.js') }}" defer></script>
    <script>
        window.mkCookieConfig = {
            cookiePolicyUrl: @json(\App\Models\Page::publicUrl($general->cookie_policy_slug), JSON_UNESCAPED_SLASHES),
            kvkkUrl: @json(\App\Models\Page::publicUrl($general->kvkk_page_slug), JSON_UNESCAPED_SLASHES),
            analyticsId: @json(filled($seo->google_analytics_id) ? $seo->google_analytics_id : null)
        };
    </script>
    <script src="{{ \App\Support\Assets::versioned('theme/js/cookie.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
