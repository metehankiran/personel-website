@extends('layouts.app')

@section('title', 'Yer İşaretlerim — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Yer İşaretlerim</div>
        <h1 class="h1">Faydalı linkler.</h1>
        <p class="lede" style="max-width:560px;">Çalışırken sık geri döndüğüm, başkalarına da değerli olabileceğini düşündüğüm kaynaklar. Konuya göre gruplanmış.</p>

        <div style="margin-top:64px;">
            <div class="stack-section">
                <div class="stack-aside">
                    <h2>Geliştirme</h2>
                    <p>Backend ve frontend için referans noktalarım.</p>
                </div>
                <div>
                    <a href="https://laravel-news.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">laravel-news.com</span><span class="uses-desc">Laravel ekosisteminde olan biten</span></a>
                    <a href="https://phptherightway.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">phptherightway.com</span><span class="uses-desc">Modern PHP'ye giriş — geriye dönük temizlik için de</span></a>
                    <a href="https://caniuse.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">caniuse.com</span><span class="uses-desc">Tarayıcı uyumluluk tablosu — vazgeçilmez</span></a>
                    <a href="https://developer.mozilla.org" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">developer.mozilla.org</span><span class="uses-desc">Web platformu için tek doğru kaynak</span></a>
                </div>
            </div>

            <div class="stack-section">
                <div class="stack-aside">
                    <h2>Tasarım</h2>
                    <p>Hızlı estetik kararlar için yardımcı kaynaklar.</p>
                </div>
                <div>
                    <a href="https://refactoringui.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">refactoringui.com</span><span class="uses-desc">Pratik UI tavsiyeleri — okuduktan sonra başka türlü düşünüyorsunuz</span></a>
                    <a href="https://www.figma.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">figma.com</span><span class="uses-desc">Tasarım ve prototyping için varsayılan aracım</span></a>
                    <a href="https://coolors.co" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">coolors.co</span><span class="uses-desc">Hızlı renk paleti üreticisi</span></a>
                </div>
            </div>

            <div class="stack-section">
                <div class="stack-aside">
                    <h2>Okuma</h2>
                    <p>Yazılım kariyerine değer katan blog ve kitaplar.</p>
                </div>
                <div>
                    <a href="https://martinfowler.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">martinfowler.com</span><span class="uses-desc">Mimari kararlar üzerine düşünmek için</span></a>
                    <a href="https://kentcdodds.com" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">kentcdodds.com</span><span class="uses-desc">Test ve frontend hakkında pratik yazılar</span></a>
                    <a href="https://overreacted.io" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">overreacted.io</span><span class="uses-desc">Dan Abramov'un derinlikli teknik yazıları</span></a>
                </div>
            </div>
        </div>
    </div></section>
@endsection
