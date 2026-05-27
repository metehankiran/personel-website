<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset('theme/favicon.svg') }}" type="image/svg+xml">

    <script>
        window.mkSearchUrl = '{{ route("search.index") }}';
        window.mkCookieConfig = {
            cookiePolicyUrl: '{{ $general->cookie_policy_slug ? route("pages.show", $general->cookie_policy_slug) : "#" }}',
            kvkkUrl: '{{ $general->kvkk_page_slug ? route("pages.show", $general->kvkk_page_slug) : "#" }}'
        };
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

    @stack('scripts')
</body>
</html>
