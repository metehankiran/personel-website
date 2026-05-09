@extends('layouts.app')

@section('title', 'Hizmetler — Metehan Kıran')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section><div class="container">
        <div class="eyebrow">Hizmetler</div>
        <h1 class="h1">Birlikte çalışmanın yolları.</h1>
        <p class="lede">Üç farklı şekilde çalışıyorum. Hepsinde de açık iletişim, gerçekçi tahminler ve teslim sonrası destek dahil.</p>
        <div class="grid-3" style="margin-top:56px;margin-bottom:80px;">
            <div class="service-card">
                <div class="service-eyebrow">Hizmet</div>
                <h3>Sıfırdan ürün</h3>
                <p class="service-desc">Fikrini production-hazır bir ürüne dönüştürürüm. Backend, frontend, deployment — hepsi.</p>
                <div class="service-bullets">
                    <div class="service-bullet"><span>Veritabanı tasarımı</span></div>
                    <div class="service-bullet"><span>API + frontend</span></div>
                    <div class="service-bullet"><span>Auth + admin paneli</span></div>
                    <div class="service-bullet"><span>Deployment + monitoring</span></div>
                </div>
                <div class="service-divider">
                    <div class="service-eyebrow" style="margin-bottom:4px;">Başlangıç</div>
                    <div style="font-size:18px;font-weight:600;margin-bottom:16px;">8 hafta</div>
                    <a href="{{ $link('contact') }}" class="btn btn-block btn-sm" style="background:var(--fg);color:var(--bg);">Konuşalım →</a>
                </div>
            </div>
            <div class="service-card featured-svc">
                <div class="service-eyebrow">En çok tercih edilen</div>
                <h3>Mevcut ürüne devam</h3>
                <p class="service-desc">Var olan bir kod tabanına geçiş ya da yeni özellik ekleme. Önce kodu okurum, sonra konuşuruz.</p>
                <div class="service-bullets">
                    <div class="service-bullet"><span>Code audit + raporlama</span></div>
                    <div class="service-bullet"><span>Yeni özellikler</span></div>
                    <div class="service-bullet"><span>Refactor + performans</span></div>
                    <div class="service-bullet"><span>Bug temizleme</span></div>
                </div>
                <div class="service-divider">
                    <div class="service-eyebrow" style="margin-bottom:4px;">Başlangıç</div>
                    <div style="font-size:18px;font-weight:600;margin-bottom:16px;">Aylık retainer</div>
                    <a href="{{ $link('contact') }}" class="btn btn-block btn-sm" style="background:var(--bg);color:var(--fg);">Konuşalım →</a>
                </div>
            </div>
            <div class="service-card">
                <div class="service-eyebrow">Hizmet</div>
                <h3>Teknik danışmanlık</h3>
                <p class="service-desc">Ekibinin teknik kararlarında yanında dururum. Mimari seçimi, code review, hiring desteği.</p>
                <div class="service-bullets">
                    <div class="service-bullet"><span>Mimari görüşmeleri</span></div>
                    <div class="service-bullet"><span>Code review</span></div>
                    <div class="service-bullet"><span>Aday değerlendirme</span></div>
                    <div class="service-bullet"><span>Yol haritası</span></div>
                </div>
                <div class="service-divider">
                    <div class="service-eyebrow" style="margin-bottom:4px;">Başlangıç</div>
                    <div style="font-size:18px;font-weight:600;margin-bottom:16px;">Saatlik</div>
                    <a href="{{ $link('contact') }}" class="btn btn-block btn-sm" style="background:var(--fg);color:var(--bg);">Konuşalım →</a>
                </div>
            </div>
        </div>
        <div class="eyebrow">Nasıl çalışırız</div>
        <div class="grid-4">
            <div class="step"><div class="step-num">01</div><h4>Tanışma</h4><p>Kısa bir görüşmede projeyi anlarım, sana içeride uygun olup olmadığımı söylerim.</p></div>
            <div class="step"><div class="step-num">02</div><h4>Teklif</h4><p>Net kapsam, tahmin ve fiyat. Her şey yazılı, sürpriz yok.</p></div>
            <div class="step"><div class="step-num">03</div><h4>İnşa</h4><p>Haftalık demo + gerçek zamanlı staging. Süreç boyunca açık kanalım.</p></div>
            <div class="step"><div class="step-num">04</div><h4>Teslim</h4><p>Production deployment, dokümantasyon, 30 gün ücretsiz destek.</p></div>
        </div>
    </div></section>
@endsection
