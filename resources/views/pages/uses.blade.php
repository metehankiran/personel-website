@extends('layouts.app')

@section('title', 'Uses — Metehan Kıran')

@section('content')
    <section><div class="container-narrow">
        <div class="eyebrow">Uses</div>
        <h1 class="h1">Setup ve araçlar.</h1>
        <p class="lede" style="max-width:560px;">Her gün kullandığım donanım ve yazılımlar. Liste güncel, son güncelleme <span class="mono" style="font-size:14px;">2026-04-12</span>.</p>
        <div style="margin-top:56px;display:flex;flex-direction:column;gap:56px;">
            <div>
                <h2 style="margin:0 0 16px;font-size:22px;font-weight:600;letter-spacing:-0.015em;">Donanım</h2>
                <div>
                    <div class="uses-row"><span class="uses-name">MacBook Pro 16" (M3 Pro)</span><span class="uses-desc">Ana iş makinem. 36GB RAM.</span></div>
                    <div class="uses-row"><span class="uses-name">LG 27" 4K Display</span><span class="uses-desc">Yan ekran, dikey kullanmıyorum.</span></div>
                    <div class="uses-row"><span class="uses-name">Keychron Q1 Pro</span><span class="uses-desc">MX Brown switch, ISO Türkçe layout.</span></div>
                    <div class="uses-row"><span class="uses-name">Logitech MX Master 3S</span><span class="uses-desc">Mouse — sessiz tıklamalar şart.</span></div>
                    <div class="uses-row"><span class="uses-name">Herman Miller Aeron</span><span class="uses-desc">Sandalye. Yatırım yapmaya değdi.</span></div>
                    <div class="uses-row"><span class="uses-name">Sony WH-1000XM5</span><span class="uses-desc">Konsantre olmak için.</span></div>
                </div>
            </div>
            <div>
                <h2 style="margin:0 0 16px;font-size:22px;font-weight:600;letter-spacing:-0.015em;">Geliştirme</h2>
                <div>
                    <div class="uses-row"><span class="uses-name">Cursor</span><span class="uses-desc">Ana editörüm. Bu sene VS Code yerine geçtim.</span></div>
                    <div class="uses-row"><span class="uses-name">iTerm2 + Zsh</span><span class="uses-desc">Tema: Dracula Pro. fzf, zoxide, starship.</span></div>
                    <div class="uses-row"><span class="uses-name">Tableplus</span><span class="uses-desc">Veritabanı GUI — Postgres ve MySQL için.</span></div>
                    <div class="uses-row"><span class="uses-name">Postman</span><span class="uses-desc">API testleri için klasik. Bruno deniyorum.</span></div>
                    <div class="uses-row"><span class="uses-name">Tower</span><span class="uses-desc">Git GUI — terminalden çıkmadığım haller hariç.</span></div>
                    <div class="uses-row"><span class="uses-name">Docker Desktop</span><span class="uses-desc">Tüm projelerde.</span></div>
                </div>
            </div>
            <div>
                <h2 style="margin:0 0 16px;font-size:22px;font-weight:600;letter-spacing:-0.015em;">Üretkenlik</h2>
                <div>
                    <div class="uses-row"><span class="uses-name">Notion</span><span class="uses-desc">Müşteri notları, proje brief metinleri.</span></div>
                    <div class="uses-row"><span class="uses-name">Linear</span><span class="uses-desc">İş takibi — görev sıralama.</span></div>
                    <div class="uses-row"><span class="uses-name">Cal.com</span><span class="uses-desc">Görüşme planlama.</span></div>
                    <div class="uses-row"><span class="uses-name">Things 3</span><span class="uses-desc">Kişisel todo.</span></div>
                    <div class="uses-row"><span class="uses-name">Raycast</span><span class="uses-desc">Spotlight yerine. Snippets çok kullanışlı.</span></div>
                    <div class="uses-row"><span class="uses-name">1Password</span><span class="uses-desc">Şifre yönetimi — tüm ekiplerle paylaşıyorum.</span></div>
                </div>
            </div>
        </div>
    </div></section>
@endsection
