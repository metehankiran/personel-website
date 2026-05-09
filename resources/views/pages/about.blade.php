@extends('layouts.app')

@section('title', 'Hakkımda — Metehan Kıran')

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    <section><div class="container">
        <div class="split-2 split-content">
            <div>
                <div class="eyebrow">Hakkımda</div>
                <h1 class="h1">5 yıldır kod yazıyor, ürün teslim ediyorum.</h1>
                <div class="prose" style="margin-top:32px;">
                    <p>İstanbul'da yaşıyorum. Lise yıllarında PHP ile başlayan kod yolculuğum, bugün Laravel, Vue.js ve .NET Core ekosistemlerinde derinleşmiş bir pratiğe dönüştü. 5 yıldır freelance olarak çalışıyorum.</p>
                    <p>E-ticaret altyapılarından kurumsal CRM'lere, dahili yönetim araçlarından mobil uygulama backend'lerine kadar geniş bir yelpazede proje teslim ettim. 40'tan fazla müşteriyle çalıştım — bazılarıyla hâlâ çalışmaya devam ediyorum.</p>
                    <p>İyi yazılım benim için <em>fark edilmeyen</em> yazılımdır: kullanıcı düşünmeden iş gören, bakımı kolay, gelecek versiyonlara dirençli kod. O yüzden modaya kapılmadan, doğrulanmış araçlarla çalışmayı tercih ediyorum.</p>
                    <p>Kodun dışında: kitap okumayı, uzun yürüyüşleri ve mekanik klavyeleri seviyorum.</p>
                </div>
                <div style="margin-top:64px;">
                    <div class="eyebrow">Zaman çizelgesi</div>
                    <div class="timeline-row"><span class="timeline-year">2026</span><span class="timeline-role">Şu an</span><span class="timeline-desc">Karavela v2 üzerinde çalışıyorum. Yeni proje alıyorum.</span></div>
                    <div class="timeline-row"><span class="timeline-year">2024</span><span class="timeline-role">Karavela</span><span class="timeline-desc">Multi-tenant e-ticaret SaaS projesini sıfırdan inşa ettim.</span></div>
                    <div class="timeline-row"><span class="timeline-year">2022</span><span class="timeline-role">Tam zamanlı freelance</span><span class="timeline-desc">Şirket bağlantımı bırakıp tamamen freelance kariyerine geçtim.</span></div>
                    <div class="timeline-row"><span class="timeline-year">2021</span><span class="timeline-role">İlk büyük müşteri</span><span class="timeline-desc">Bir kurumsal CRM projesini 6 ayda teslim ettim.</span></div>
                    <div class="timeline-row"><span class="timeline-year">2020</span><span class="timeline-role">Profesyonel başlangıç</span><span class="timeline-desc">Yarı zamanlı freelance işler almaya başladım.</span></div>
                    <div class="timeline-row"><span class="timeline-year">2018</span><span class="timeline-role">Kodla tanışma</span><span class="timeline-desc">Lise yıllarında PHP ile başladım.</span></div>
                </div>
            </div>
            <aside class="sidebar" style="display:flex;flex-direction:column;gap:24px;">
                <div class="sidebar-portrait">[ portre fotoğrafı ]</div>
                <div class="sidebar-card">
                    <div class="eyebrow-sm" style="margin-bottom:16px;">Hızlı bilgiler</div>
                    <div style="display:flex;flex-direction:column;gap:12px;font-size:13px;">
                        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:10px;"><span class="sub">Lokasyon</span><span style="font-weight:500;">İstanbul, TR</span></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:10px;"><span class="sub">Çalışma şekli</span><span style="font-weight:500;">Uzaktan</span></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:10px;"><span class="sub">Diller</span><span style="font-weight:500;">TR · EN</span></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:10px;"><span class="sub">Tecrübe</span><span style="font-weight:500;">5+ yıl</span></div>
                        <div style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid var(--border);padding-bottom:10px;"><span class="sub">Müsaitlik</span><span style="font-weight:500;">Ocak 2026</span></div>
                    </div>
                    <a href="{{ $link('cv') }}" class="btn btn-primary btn-block btn-sm" style="margin-top:20px;">CV'yi indir ↓</a>
                </div>
            </aside>
        </div>
    </div></section>
@endsection
