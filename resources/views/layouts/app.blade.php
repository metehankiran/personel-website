<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&family=Playfair+Display:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('theme/css/style.css') }}">
    <link rel="icon" href="{{ asset('theme/favicon.svg') }}" type="image/svg+xml">

    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main-content">Ana içeriğe atla</a>

    <x-site-header />

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    <x-site-footer />

    <script src="{{ asset('theme/js/main.js') }}"></script>
    <script src="{{ asset('theme/js/search.js') }}" defer></script>
    <script src="{{ asset('theme/js/cookie.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
