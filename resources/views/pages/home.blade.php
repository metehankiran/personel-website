@extends('layouts.app')

@section('title', 'Metehan Kıran — Full-stack Developer')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section><div class="container">
        <div class="split-2 split-hero">
            <div>
                <div class="eyebrow"><span style="display:inline-flex;align-items:center;gap:8px;"><span style="width:6px;height:6px;border-radius:3px;background:var(--success);"></span>Yeni proje alıyor</span></div>
                <h1 class="h1-xl">Bağımsız <em>full-stack</em> developer.<br/><span class="dim">Ürünleri sıfırdan teslim ederim.</span></h1>
                <p class="lede" style="max-width:540px;margin-top:28px;">5 yıldır Laravel, Vue ve .NET Core ile çalışıyorum. E-ticaretten kurumsal CRM'lere — küçük ekiplerle ya da tek başıma.</p>
                <div style="margin-top:32px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ $link('contact') }}" class="btn btn-primary">İletişime geç →</a>
                    <a href="{{ $link('projects') }}" class="btn btn-secondary">Projeleri gör</a>
                </div>
            </div>
            <div class="def-stack">
                <div class="def-row"><span class="def-key">Tecrübe</span><span class="def-val">5+ yıl</span></div>
                <div class="def-row"><span class="def-key">Lokasyon</span><span class="def-val">İstanbul, TR</span></div>
                <div class="def-row"><span class="def-key">Müsaitlik</span><span class="def-val">Ocak 2026</span></div>
                <div class="def-row"><span class="def-key">Çalıştığım</span><span class="def-val">40+ müşteri</span></div>
            </div>
        </div>
    </div></section>

    <section><div class="container">
        <div class="section-head"><div class="eyebrow-sm">Öne Çıkan · 2024</div><a href="{{ $link('projects') }}" class="link-arrow">Vakayı oku →</a></div>
        <div class="featured">
            <div>
                <div class="eyebrow-sm" style="margin-bottom:16px;">SaaS · E-ticaret</div>
                <h2 style="margin:0;font-size:44px;font-weight:500;letter-spacing:-0.03em;line-height:1.05;">Karavela</h2>
                <p style="margin-top:16px;font-size:16px;color:var(--dim);line-height:1.6;">Multi-tenant e-ticaret altyapısı. Laravel monolith üzerinde 200+ aktif kiracıyı tek codebase ile besliyor.</p>
                <div style="display:flex;gap:20px;margin-top:28px;flex-wrap:wrap;">
                    <span class="metric"><strong>200+</strong> <span class="dim">kiracı</span></span>
                    <span class="metric"><strong>2.4M</strong> <span class="dim">aylık istek</span></span>
                    <span class="metric"><strong>99.97%</strong> <span class="dim">uptime</span></span>
                </div>
            </div>
            <div class="featured-image">[ ürün ekran görüntüsü ]</div>
        </div>
    </div></section>

    <section><div class="container">
        <div class="section-head"><h2>Diğer projeler</h2><a href="{{ $link('projects') }}" class="link-arrow">Tümünü gör →</a></div>
        <div class="grid-3">
            <a href="{{ $link('projects') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Flotaki</h3><span class="card-year">2023</span></div><div class="card-desc">Filo yönetim CRM</div><span class="chip">CRM</span></a>
            <a href="{{ $link('projects') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Rezerv</h3><span class="card-year">2024</span></div><div class="card-desc">Restoran rezervasyon API</div><span class="chip">API</span></a>
            <a href="{{ $link('projects') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Atelye</h3><span class="card-year">2023</span></div><div class="card-desc">Mimarlık ofisi sitesi</div><span class="chip">Web</span></a>
        </div>
    </div></section>

    <section><div class="container">
        <div class="cta">
            <h2>Bir proje fikrin var mı?</h2>
            <p>Konuşalım. Genelde 24 saat içinde dönerim.</p>
            <a href="{{ $link('contact') }}" class="btn btn-primary" style="margin-top:28px;">İletişime geç →</a>
        </div>
    </div></section>
@endsection
