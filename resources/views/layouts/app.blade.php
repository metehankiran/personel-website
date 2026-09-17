<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', $seo->meta_description ?? config('app.name') . ' — Bağımsız full-stack developer.')">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="@yield('title', config('app.name'))">
    <meta property="og:description" content="@yield('meta_description', $seo->meta_description ?? config('app.name') . ' — Bağımsız full-stack developer.')">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:locale" content="tr_TR">
    <meta property="og:image" content="@yield('og_image', \App\Support\Images::og($seo->og_image_path ?? null))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="@yield('og_image', \App\Support\Images::og($seo->og_image_path ?? null))">

    <link rel="icon" href="{{ \App\Support\Images::url($general->favicon_path ?? null, 'favicon') }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <script>
        (function () {
            var mode = null;
            try { mode = localStorage.getItem('mk-theme'); } catch (e) {}
            if (!mode || mode === 'system') {
                mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', mode);
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
            cookiePolicyUrl: '{{ $general->cookie_policy_slug ? route("pages.show", $general->cookie_policy_slug) : "#" }}',
            kvkkUrl: '{{ $general->kvkk_page_slug ? route("pages.show", $general->kvkk_page_slug) : "#" }}'
        };
    </script>
    <script src="{{ \App\Support\Assets::versioned('theme/js/cookie.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
