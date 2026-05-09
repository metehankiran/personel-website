@extends('layouts.app')

@section('title', 'CV — Metehan Kıran')

@section('content')
    <section><div class="container-narrow">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:48px;flex-wrap:wrap;margin-bottom:56px;">
            <div>
                <div class="eyebrow">CV</div>
                <h1 class="h1-md">Metehan Kıran</h1>
                <p style="margin:12px 0 0;font-size:18px;color:var(--dim);">Full-stack developer · İstanbul, TR</p>
            </div>
            <a href="#" class="btn btn-primary">↓ PDF olarak indir</a>
        </div>
        <div style="display:flex;flex-direction:column;gap:56px;">
            <div class="split-cv">
                <div class="eyebrow-sm">Özet</div>
                <p style="margin:0;font-size:16px;line-height:1.7;opacity:0.92;">5 yıllık deneyime sahip bağımsız full-stack developer. Laravel, Vue.js ve .NET Core ekosistemlerinde uzmanlaştım. 40+ müşteriyle — KOBİ'lerden kurumsal şirketlere — production-hazır ürünler teslim ettim.</p>
            </div>
            <div class="split-cv">
                <div class="eyebrow-sm">Tecrübe</div>
                <div style="display:flex;flex-direction:column;gap:32px;">
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:16px;margin-bottom:4px;">
                            <h3 style="margin:0;font-size:17px;font-weight:600;">Bağımsız Full-stack Developer</h3>
                            <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;white-space:nowrap;">2022 — bugün</span>
                        </div>
                        <div style="font-size:14px;color:var(--dim);margin-bottom:8px;">Freelance</div>
                        <p style="margin:0;font-size:14px;opacity:0.85;line-height:1.6;">40+ müşteriyle e-ticaret, CRM, SaaS ve dahili araç projeleri. Laravel, Vue 3, .NET Core, Postgres.</p>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:16px;margin-bottom:4px;">
                            <h3 style="margin:0;font-size:17px;font-weight:600;">Senior Backend Developer</h3>
                            <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;white-space:nowrap;">2020 — 2022</span>
                        </div>
                        <div style="font-size:14px;color:var(--dim);margin-bottom:8px;">Trio Software</div>
                        <p style="margin:0;font-size:14px;opacity:0.85;line-height:1.6;">B2B SaaS ürünleri. PHP/Laravel ekibi. Mimari kararlar, code review, mentorship.</p>
                    </div>
                    <div>
                        <div style="display:flex;justify-content:space-between;align-items:baseline;gap:16px;margin-bottom:4px;">
                            <h3 style="margin:0;font-size:17px;font-weight:600;">Junior Developer</h3>
                            <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;white-space:nowrap;">2019 — 2020</span>
                        </div>
                        <div style="font-size:14px;color:var(--dim);margin-bottom:8px;">Atak Yazılım</div>
                        <p style="margin:0;font-size:14px;opacity:0.85;line-height:1.6;">WordPress, vanilla PHP, MySQL. İlk freelance işlerimi alarak başladığım dönem.</p>
                    </div>
                </div>
            </div>
            <div class="split-cv">
                <div class="eyebrow-sm">Yetkinlikler</div>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:grid;grid-template-columns:100px 1fr;gap:16px;align-items:baseline;"><span style="font-size:13px;color:var(--dim);">Backend</span><span style="font-size:14px;font-weight:500;">Laravel · .NET Core · Node.js · PHP</span></div>
                    <div style="display:grid;grid-template-columns:100px 1fr;gap:16px;align-items:baseline;"><span style="font-size:13px;color:var(--dim);">Frontend</span><span style="font-size:14px;font-weight:500;">Vue 3 · Inertia.js · React · Tailwind</span></div>
                    <div style="display:grid;grid-template-columns:100px 1fr;gap:16px;align-items:baseline;"><span style="font-size:13px;color:var(--dim);">Veritabanı</span><span style="font-size:14px;font-weight:500;">PostgreSQL · MySQL · Redis · MSSQL</span></div>
                    <div style="display:grid;grid-template-columns:100px 1fr;gap:16px;align-items:baseline;"><span style="font-size:13px;color:var(--dim);">Altyapı</span><span style="font-size:14px;font-weight:500;">Docker · GitHub Actions · Hetzner · CloudFlare</span></div>
                </div>
            </div>
            <div class="split-cv">
                <div class="eyebrow-sm">Eğitim</div>
                <div>
                    <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
                        <h3 style="margin:0;font-size:16px;font-weight:600;">Bilgisayar Mühendisliği, Lisans</h3>
                        <span style="font-size:12px;color:var(--sub);font-variant-numeric:tabular-nums;">2017 — 2021</span>
                    </div>
                    <div style="font-size:14px;color:var(--dim);">İstanbul Teknik Üniversitesi</div>
                </div>
            </div>
            <div class="split-cv">
                <div class="eyebrow-sm">Diller</div>
                <div style="font-size:14px;line-height:2;">
                    <div>Türkçe — anadil</div>
                    <div>İngilizce — ileri seviye (C1)</div>
                </div>
            </div>
        </div>
    </div></section>
@endsection
