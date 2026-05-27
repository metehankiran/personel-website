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
    <meta property="og:type" content="website">
    <meta property="og:locale" content="tr_TR">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="icon" href="{{ asset('theme/favicon.svg') }}" type="image/svg+xml">
    <link rel="canonical" href="{{ url()->current() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>
<body class="font-sans text-sm leading-normal tracking-tight bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 antialiased min-h-screen flex flex-col">
    <x-site-header />

    <main id="main-content" tabindex="-1" class="flex-1">
        @yield('content')
    </main>

    <x-site-footer />

    <script src="{{ asset('theme/js/main.js') }}"></script>
    <script>window.mkSearchUrl = '{{ route("search.index") }}';</script>
    <script src="{{ asset('theme/js/search.js') }}" defer></script>
    <script>
        window.mkCookieConfig = {
            cookiePolicyUrl: '{{ $general->cookie_policy_slug ? route("pages.show", $general->cookie_policy_slug) : "#" }}',
            kvkkUrl: '{{ $general->kvkk_page_slug ? route("pages.show", $general->kvkk_page_slug) : "#" }}'
        };
    </script>
    <script src="{{ asset('theme/js/cookie.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
