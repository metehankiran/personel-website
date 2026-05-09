@extends('layouts.app')

@section('title', 'Bakım — Metehan Kıran')

@section('content')
    <section style="padding-top:80px;padding-bottom:80px;">
        <div class="container-narrow" style="text-align:center;">
            <div class="error-code" style="font-family:'JetBrains Mono', monospace;font-size:48px;letter-spacing:0;">~ ~ ~</div>
            <h1 class="h1-xl" style="margin:24px 0 0;">Kısa bir bakım yapıyorum.</h1>
            <p class="lede" style="margin:24px auto 0;max-width:520px;">Site şu an güncelleniyor. Genelde 30 dakika sürmez. Acelesi olan iş için doğrudan email atabilirsin.</p>
            <div style="margin-top:40px;">
                <a href="mailto:merhaba@metehankiran.dev" class="btn btn-primary">Email gönder →</a>
            </div>
            <div style="margin-top:64px;display:inline-grid;grid-template-columns:auto auto;gap:8px 24px;text-align:left;font-size:13px;color:var(--dim);">
                <span class="sub">Durum</span>
                <span style="display:inline-flex;align-items:center;gap:6px;"><span style="width:7px;height:7px;border-radius:4px;background:var(--success);animation:blink 1.5s infinite;"></span>Çalışmalar sürüyor</span>
            </div>
        </div>
    </section>
    <style>@keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }</style>
@endsection
