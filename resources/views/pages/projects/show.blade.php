@extends('layouts.app')

@section('title', 'Karavela — Projeler — Metehan Kıran')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section><div class="container">
        <div style="margin-bottom:48px;"><a href="{{ route('projects') }}" class="back-link">← Tüm projeler</a></div>
        <div class="split-2 split-hero">
            <div>
                <div class="eyebrow"><span class="chip">SaaS</span> <span class="chip" style="margin-left:6px;">E-ticaret</span></div>
                <h1 class="h1-xl">Karavela</h1>
                <p class="lede" style="max-width:560px;">Multi-tenant e-ticaret SaaS — 200+ kiracıyı tek codebase ile besleyen Laravel monolith.</p>
                <div style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="#" class="btn btn-primary">Canlı projeyi gör →</a>
                    <a href="{{ $link('contact') }}" class="btn btn-secondary">Benzer bir şey yap</a>
                </div>
            </div>
            <div class="def-stack">
                <div class="def-row"><span class="def-key">Müşteri</span><span class="def-val">Karavela Inc.</span></div>
                <div class="def-row"><span class="def-key">Yıl</span><span class="def-val">2024</span></div>
                <div class="def-row"><span class="def-key">Süre</span><span class="def-val">5 ay</span></div>
                <div class="def-row"><span class="def-key">Rolüm</span><span class="def-val">Lead developer</span></div>
                <div class="def-row"><span class="def-key">Stack</span><span class="def-val">Laravel · Vue 3 · Postgres</span></div>
            </div>
        </div>
        <div class="featured-image" style="margin-top:64px;aspect-ratio:16/9;border-radius:16px;">[ ana ürün ekranı ]</div>
    </div></section>

    <section><div class="container">
        <div class="grid-3 metrics-row">
            <div class="metric-card"><div class="metric-num">200+</div><div class="metric-label">Aktif kiracı</div></div>
            <div class="metric-card"><div class="metric-num">2.4M</div><div class="metric-label">Aylık istek</div></div>
            <div class="metric-card"><div class="metric-num">99.97%</div><div class="metric-label">Uptime (12 ay)</div></div>
        </div>
    </div></section>

    <section><div class="container-narrow">
        <div class="case-block">
            <div class="eyebrow">Problem</div>
            <h2 class="h1-md">Tek bir e-ticaret altyapısı, yüzlerce farklı marka.</h2>
            <p class="prose-p">Karavela ekibi, niş pazarlardaki KOBİ'lere "kendi mağazan, ama altyapıya hiç dokunmadan" deneyimi sunmak istiyordu. Her müşteri için ayrı kurulum yapmak ölçeklenebilir değildi — hem operasyonel maliyet, hem güncel kalma sorunu.</p>
            <p class="prose-p">Çözüm: tek codebase, çoklu kiracı (multi-tenant) bir SaaS. Ama hangi tenancy stratejisi? Veritabanı başına kiracı? Schema başına? Tek veritabanı + kiracı kolonu?</p>
        </div>

        <div class="case-block">
            <div class="eyebrow">Yaklaşım</div>
            <h2 class="h1-md">Tek veritabanı, tenant_id, ama akıllı.</h2>
            <p class="prose-p">Ekosistemi tartıştık. <em>Tenancy for Laravel</em> paketi tenant başına veritabanı modeline odaklanıyor; bizim için maintenance hızlı katlanıyordu. Onun yerine row-level isolation tercih ettik:</p>
            <ul class="case-list">
                <li>Her tabloda <code>tenant_id</code> kolonu</li>
                <li>Eloquent global scope ile otomatik filtreleme</li>
                <li>Subdomain bazlı tenant resolution (<code>marka.karavela.com</code>)</li>
                <li>Migration ve seed'ler tek veritabanı için yazılıyor</li>
                <li>Backup tek script, restore tek script</li>
            </ul>
        </div>

        <div class="case-block">
            <div class="eyebrow">Sonuç</div>
            <h2 class="h1-md">5 ayda canlı, 12 ay sonra hâlâ tek dev.</h2>
            <p class="prose-p">İlk MVP 5 ayda canlıya çıktı. 12 ay sonrasında, ekipte hâlâ ben varım — başka geliştirici eklemek gerekmedi. Yeni özellik eklemek için kiracı sayısı engel değil; çünkü hepsi aynı kod.</p>
            <p class="prose-p">İlk yıl içinde en büyük risk: bir migration sorunu tüm kiracıları etkiler. Bu yüzden CI'da migration linting, staging'de production verisinin anonimleştirilmiş bir kopyası ile test ediyoruz.</p>
        </div>
    </div></section>

    <section><div class="container">
        <div class="grid-2" style="gap:32px;">
            <div class="featured-image">[ admin paneli ]</div>
            <div class="featured-image">[ checkout akışı ]</div>
        </div>
    </div></section>

    <section><div class="container-narrow">
        <div class="quote-card big" style="margin:0 auto;max-width:720px;">
            <div class="quote-mark">"</div>
            <p class="quote-text">Metehan ile çalışmak, ekibimizden biriyle çalışmak gibi. Sadece kod değil, ürün düşüncesi de ekliyor. Karavela'yı ondan başkasının inşa etmesini hayal edemiyoruz.</p>
            <div class="quote-author"><div class="quote-name">Selin Akın</div><div class="quote-role">Co-founder, Karavela</div></div>
        </div>
    </div></section>

    <section><div class="container">
        <div class="section-head"><h2>Diğer projeler</h2><a href="{{ route('projects') }}" class="link-arrow">Tümünü gör →</a></div>
        <div class="grid-3">
            <a href="{{ route('projects.show', 'flotaki') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Flotaki</h3><span class="card-year">2023</span></div><div class="card-desc">Filo yönetim CRM</div><span class="chip">CRM</span></a>
            <a href="{{ route('projects.show', 'rezerv') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Rezerv</h3><span class="card-year">2024</span></div><div class="card-desc">Restoran rezervasyon API</div><span class="chip">API</span></a>
            <a href="{{ route('projects.show', 'patika') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Patika</h3><span class="card-year">2022</span></div><div class="card-desc">E-eğitim platformu</div><span class="chip">SaaS</span></a>
        </div>
    </div></section>
@endsection
