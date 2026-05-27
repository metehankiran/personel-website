@extends('layouts.app')

@section('title', 'Hizmetler — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Hizmetler</div>
        <h1 class="h1">Birlikte çalışmanın yolları.</h1>
        <p class="lede">Üç farklı şekilde çalışıyorum. Hepsinde de açık iletişim, gerçekçi tahminler ve teslim sonrası destek dahil.</p>
        <div class="services-grid" style="margin-top:56px;margin-bottom:80px;">
            @foreach($services as $service)
                <div class="service-card">
                    <div class="service-eyebrow">{{ $service->badge ?? 'Hizmet' }}</div>
                    <h3>{{ $service->title }}</h3>
                    <p class="service-desc">{{ $service->description }}</p>
                    <div class="service-bullets">
                        @foreach($service->features as $feature)
                            <div class="service-bullet"><span>{{ $feature }}</span></div>
                        @endforeach
                    </div>
                    <div class="service-divider">
                        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:16px;">
                            <div>
                                <div class="service-eyebrow" style="margin-bottom:4px;">Fiyatlandırma</div>
                                <div style="font-size:18px;font-weight:600;">{{ $service->pricing }}</div>
                            </div>
                            @if($service->duration)
                                <div style="text-align:right;">
                                    <div class="service-eyebrow" style="margin-bottom:4px;">Süre</div>
                                    <div style="font-size:18px;font-weight:600;">{{ $service->duration }}</div>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('contact') }}" class="btn btn-block btn-sm" style="background:var(--fg);color:var(--bg);">Konuşalım →</a>
                    </div>
                </div>
            @endforeach
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
