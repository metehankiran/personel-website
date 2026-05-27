<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: ['selector', '[data-theme="dark"]'],
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Inter Tight"', '"Inter"', '-apple-system', 'BlinkMacSystemFont', 'system-ui', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'ui-monospace', 'monospace'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                    },
                },
            },
        }
    </script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}">
    <link rel="icon" href="{{ asset('theme/favicon.svg') }}" type="image/svg+xml">

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
    <script>lucide.createIcons();</script>
</body>
</html>
