<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bakım modu — {{ config('site.author.name') }}</title>
    <meta name="description" content="Site şu an güncelleniyor.">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0a0a0a" media="(prefers-color-scheme: dark)">
    <style>
        :root {
            --bg: #ffffff;
            --fg: #0a0a0a;
            --dim: #525252;
            --sub: #737373;
            --border: #ebebeb;
            --success: #16a34a;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0a0a0a;
                --fg: #fafafa;
                --dim: #a3a3a3;
                --sub: #737373;
                --border: #1f1f1f;
                --success: #22c55e;
            }
        }
        *, *::before, *::after { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }
        body {
            font-family: "Inter Tight", "Inter", -apple-system, BlinkMacSystemFont, system-ui, sans-serif;
            background: var(--bg);
            color: var(--fg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            letter-spacing: -0.005em;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }
        main {
            max-width: 520px;
            width: 100%;
            text-align: center;
        }
        .mark {
            width: 32px;
            height: 32px;
            border-radius: 16px;
            background: var(--fg);
            position: relative;
            margin: 0 auto 40px;
        }
        .mark::after {
            content: "";
            position: absolute;
            inset: 6px;
            background: var(--bg);
            border-radius: 10px;
        }
        .pulse {
            font-family: ui-monospace, "JetBrains Mono", Menlo, Consolas, monospace;
            font-size: 40px;
            letter-spacing: 0.15em;
            color: var(--sub);
            margin: 0 0 20px;
        }
        h1 {
            font-size: clamp(36px, 6vw, 56px);
            line-height: 1.0;
            font-weight: 500;
            letter-spacing: -0.035em;
            margin: 0;
            text-wrap: balance;
        }
        p {
            font-size: 17px;
            color: var(--dim);
            line-height: 1.55;
            margin: 24px auto 0;
            max-width: 460px;
        }
        .cta {
            display: flex;
            width: fit-content;
            margin: 40px auto 0;
            align-items: center;
            gap: 6px;
            padding: 13px 22px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            background: var(--fg);
            color: var(--bg);
            text-decoration: none;
            transition: opacity .15s;
        }
        .cta:hover { opacity: 0.85; }
        .status {
            display: flex;
            width: fit-content;
            margin: 40px auto 0;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--sub);
        }
        .dot {
            width: 7px;
            height: 7px;
            border-radius: 4px;
            background: var(--success);
            animation: blink 1.5s infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
    </style>
</head>
<body>
    <main>
        <div class="mark" aria-hidden="true"></div>
        <div class="pulse" aria-hidden="true">~ ~ ~</div>
        <h1>Kısa bir bakım yapıyorum.</h1>
        <p>Site şu an güncelleniyor. Genelde 30 dakika sürmez. Acelesi olan iş için doğrudan email atabilirsin.</p>
        <a class="cta" href="mailto:{{ config('site.author.email') }}">Email gönder →</a>
        <div class="status">
            <span class="dot" aria-hidden="true"></span>
            <span>Çalışmalar sürüyor</span>
        </div>
    </main>
</body>
</html>
