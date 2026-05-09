@extends('layouts.app')

@section('title', 'Stack — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Stack</div>
        <h1 class="h1">Kullandığım teknolojiler.</h1>
        <p class="lede">Modaya kapılmadan, doğrulanmış araçlarla çalışıyorum. Her teknolojinin yanında o araçla geçirdiğim zamanın yansıması var.</p>
        <div style="margin-top:64px;">
            <div class="stack-section">
                <div class="stack-aside"><h2>Backend</h2><p>Asıl evim. Karmaşık iş mantığı, veri modelleme, API tasarımı.</p></div>
                <div>
                    <div class="stack-row"><span class="stack-name">Laravel</span><span class="stack-desc">Ana çerçevem. 5 yıldır.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                    <div class="stack-row"><span class="stack-name">.NET Core</span><span class="stack-desc">Kurumsal projeler için.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">Node.js</span><span class="stack-desc">Hafif servisler ve real-time.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">PHP</span><span class="stack-desc">Vanilla legacy sürdürme.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                </div>
            </div>
            <div class="stack-section">
                <div class="stack-aside"><h2>Frontend</h2><p>Yatkın olduğum tarafta sade, erişilebilir UI.</p></div>
                <div>
                    <div class="stack-row"><span class="stack-name">Vue 3</span><span class="stack-desc">Composition API + Pinia.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">Inertia.js</span><span class="stack-desc">Laravel ile birlikte favorim.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                    <div class="stack-row"><span class="stack-name">React</span><span class="stack-desc">Ne zaman gerekirse.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">Tailwind CSS</span><span class="stack-desc">Standart aracım.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                </div>
            </div>
            <div class="stack-section">
                <div class="stack-aside"><h2>Veritabanı</h2><p>İlişkisel veri tasarımı, performans ayarları.</p></div>
                <div>
                    <div class="stack-row"><span class="stack-name">PostgreSQL</span><span class="stack-desc">Yeni projelerde varsayılan.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                    <div class="stack-row"><span class="stack-name">MySQL</span><span class="stack-desc">Mevcut projelerde.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span></div></div>
                    <div class="stack-row"><span class="stack-name">Redis</span><span class="stack-desc">Cache + queue + session.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">MSSQL</span><span class="stack-desc">.NET projeleri için.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span><span class="stack-pip"></span></div></div>
                </div>
            </div>
            <div class="stack-section">
                <div class="stack-aside"><h2>Altyapı</h2><p>Production deploy ve sürdürülebilirlik.</p></div>
                <div>
                    <div class="stack-row"><span class="stack-name">Docker</span><span class="stack-desc">Hemen her projede.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">GitHub Actions</span><span class="stack-desc">CI/CD pipeline.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">Hetzner / DO</span><span class="stack-desc">Self-managed VPS.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                    <div class="stack-row"><span class="stack-name">CloudFlare</span><span class="stack-desc">CDN + DNS + R2.</span><div class="stack-bar"><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip on"></span><span class="stack-pip"></span></div></div>
                </div>
            </div>
        </div>
    </div></section>
@endsection
