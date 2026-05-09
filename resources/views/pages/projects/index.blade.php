@extends('layouts.app')

@section('title', 'Projeler — Metehan Kıran')

@php
    $detail = fn (): string => Route::has('projects.show') ? route('projects.show', 'karavela') : '#';
@endphp

@section('content')
    <section><div class="container">
        <div class="eyebrow">Projeler</div>
        <h1 class="h1">Yaptığım işler.</h1>
        <p class="lede">5 yıllık freelance kariyerimden seçili çalışmalar. Hepsi production'da, büyük çoğunluğu hâlâ canlı.</p>
        <div class="filters" data-filter-group="proj">
            <button class="chip-lg active" data-filter="all">Hepsi (9)</button>
            <button class="chip-lg" data-filter="SaaS">SaaS (2)</button>
            <button class="chip-lg" data-filter="CRM">CRM (2)</button>
            <button class="chip-lg" data-filter="API">API (3)</button>
            <button class="chip-lg" data-filter="Web">Web (2)</button>
        </div>
        <div class="grid-3" style="margin-top:48px;">
            <a href="{{ $detail() }}" class="card card-wide" data-filter-target="proj" data-filter-value="SaaS">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Karavela</h3><span class="card-year">2024</span></div>
                <div class="card-desc">Multi-tenant e-ticaret SaaS — 200+ kiracı, Laravel monolith.</div>
                <div class="card-meta"><span class="chip">SaaS</span><span class="mono">Laravel · Vue · Postgres</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="CRM">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Flotaki</h3><span class="card-year">2023</span></div>
                <div class="card-desc">Filo yönetim CRM, .NET Core API + Vue 3.</div>
                <div class="card-meta"><span class="chip">CRM</span><span class="mono">.NET Core · Vue 3</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="API">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Rezerv</h3><span class="card-year">2024</span></div>
                <div class="card-desc">Restoran rezervasyon API + admin panel.</div>
                <div class="card-meta"><span class="chip">API</span><span class="mono">Laravel · Redis</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="Web">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Atelye</h3><span class="card-year">2023</span></div>
                <div class="card-desc">Mimarlık ofisi için kurumsal site + headless CMS.</div>
                <div class="card-meta"><span class="chip">Web</span><span class="mono">Nuxt · Sanity</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="API">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Tezgah</h3><span class="card-year">2022</span></div>
                <div class="card-desc">Yerel pazaryeri için iOS + web mobil API.</div>
                <div class="card-meta"><span class="chip">API</span><span class="mono">Laravel · MySQL</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="SaaS">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Patika</h3><span class="card-year">2022</span></div>
                <div class="card-desc">E-eğitim platformu, video streaming dahil.</div>
                <div class="card-meta"><span class="chip">SaaS</span><span class="mono">Laravel · Vue · S3</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="CRM">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Kasa</h3><span class="card-year">2021</span></div>
                <div class="card-desc">POS yönetim panosu, çoklu şube.</div>
                <div class="card-meta"><span class="chip">CRM</span><span class="mono">.NET Core · React</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="Web">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Kapsül</h3><span class="card-year">2021</span></div>
                <div class="card-desc">Newsletter ve içerik dağıtım aracı.</div>
                <div class="card-meta"><span class="chip">Web</span><span class="mono">Laravel · Inertia</span></div>
            </a>
            <a href="{{ $detail() }}" class="card" data-filter-target="proj" data-filter-value="API">
                <div class="card-image"></div>
                <div class="card-title-row"><h3 class="card-title">Migros API</h3><span class="card-year">2020</span></div>
                <div class="card-desc">Kurumsal stok entegrasyonu.</div>
                <div class="card-meta"><span class="chip">API</span><span class="mono">.NET · MSSQL</span></div>
            </a>
        </div>
    </div></section>
@endsection
