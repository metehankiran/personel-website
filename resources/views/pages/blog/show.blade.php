@extends('layouts.app')

@section('title', "Laravel'de gerçekten lazım olan paketler — Metehan Kıran")

@section('content')
    <article class="article">
        <header class="article-header">
            <div class="container-narrow">
                <div class="article-meta-top">
                    <a href="{{ route('blog') }}" class="back-link">← Blog</a>
                    <span class="chip">Laravel</span>
                </div>
                <h1 class="article-title">Laravel'de gerçekten lazım olan paketler</h1>
                <p class="article-lede">Her projede tekrar tekrar kurduğum 7 paket — neden vazgeçilmez, hangi alternatifleri denedim, ne zaman kullanmamak gerekir.</p>
                <div class="article-byline">
                    <div class="byline-author">
                        <span class="brand-mark"></span>
                        <div>
                            <div class="byline-name">Metehan Kıran</div>
                            <div class="byline-meta">12 Kasım 2024 · 6 dk okuma</div>
                        </div>
                    </div>
                    <div class="article-share">
                        <button class="share-btn" type="button" aria-label="Twitter'da paylaş">𝕏</button>
                        <button class="share-btn" type="button" aria-label="LinkedIn'de paylaş">in</button>
                        <button class="share-btn" type="button" aria-label="Linki kopyala">⌘</button>
                    </div>
                </div>
            </div>
        </header>

        <div class="container-narrow article-body">
            <div class="article-toc-wrap">
                <aside class="article-toc" aria-label="İçindekiler">
                    <div class="eyebrow-sm" style="margin-bottom:14px;">İçindekiler</div>
                    <ol>
                        <li><a href="#giris" class="active">Giriş</a></li>
                        <li><a href="#paket-1">1. Spatie Laravel Permission</a></li>
                        <li><a href="#paket-2">2. Laravel Telescope</a></li>
                        <li><a href="#paket-3">3. Pest</a></li>
                        <li><a href="#paket-4">4. Laravel Horizon</a></li>
                        <li><a href="#paket-5">5. Laravel Scout</a></li>
                        <li><a href="#paket-6">6. Spatie Media Library</a></li>
                        <li><a href="#paket-7">7. Laravel Pulse</a></li>
                        <li><a href="#sonuc">Sonuç</a></li>
                    </ol>
                </aside>
                <div class="prose-article">
                    <p class="lead-para" id="giris">5 yıllık Laravel pratiğimde, her yeni projeye başladığımda otomatik kurduğum bir avuç paket var. Bunların hiçbiri "havalı olduğu için" değil — hepsi gerçek bir problemi çözüyor ve yıllar içinde kendini ispatladılar.</p>

                    <p>Bu listenin amacı bir "must-have" katalogu çıkartmak değil. Daha çok, hangi problemi hangi paketle çözdüğümü ve neden bu paketi seçtiğimi anlatmak. Listedeki paketlerin alternatiflerini de denedim — kararı paylaşmak istiyorum.</p>

                    <h2 id="paket-1">1. Spatie Laravel Permission</h2>
                    <p>Authorization yönetimi, üzerinde 10 dakika düşünülmemiş bir konuysa hayatınız çok zorlaşır. Bu paket, role + permission tabanlı yetkilendirmeyi standardize ediyor.</p>
<pre><code>composer require spatie/laravel-permission

// User modeli
use HasRoles;

// Kullanım
$user-&gt;assignRole('editor');
$user-&gt;can('publish-articles');</code></pre>
                    <p>Alternatifi: kendin yazmak. Denedim, geri döndüm.</p>
                    <blockquote>Authorization, ürünün mantığıyla en sıkı bağı olan ama en çok ihmal edilen kısımdır. Standart bir paket kullanmak takım hatalarını radikal düşürüyor.</blockquote>

                    <h2 id="paket-2">2. Laravel Telescope</h2>
                    <p>Local geliştirmede mutlaka açık tutarım. SQL, request, exception, mail — hepsi tek bir dashboard'ta. Production'da kapatın, çünkü performans maliyeti var ve hassas veri sızdırır.</p>

                    <h2 id="paket-3">3. Pest</h2>
                    <p>PHPUnit'in syntax'ı bana hep ağır geldi. Pest ile test yazmak gerçekten zevkli hale geliyor.</p>
<pre><code>it('logs user in', function () {
    $user = User::factory()-&gt;create();
    actingAs($user)-&gt;get('/dashboard')-&gt;assertOk();
});</code></pre>

                    <h2 id="paket-4">4. Laravel Horizon</h2>
                    <p>Queue işlerini takip etmek için. Redis ile kullanmak şart, ama zaten Redis kullanıyorsanız Horizon hayatı kolaylaştırıyor.</p>

                    <h2 id="paket-5">5. Laravel Scout</h2>
                    <p>Full-text arama. Algolia ile başlardım ama maliyet yüksek; bugün Meilisearch ile self-host ediyorum.</p>

                    <h2 id="paket-6">6. Spatie Media Library</h2>
                    <p>Dosya yükleme + thumbnail + multiple disk desteği. Manuel S3 entegrasyonu yapmaktan kurtarıyor.</p>

                    <h2 id="paket-7">7. Laravel Pulse</h2>
                    <p>Production'da app sağlığını izlemek için. Sentry'nin yaptığı şeyin ücretsiz versiyonu sayılabilir, ama daha sınırlı.</p>

                    <h2 id="sonuc">Sonuç</h2>
                    <p>Her paket, ekstra bir bağımlılık demek. Ama bu listedeki 7 paketin hiçbiri için "keşke yazmasaydım" demedim. Aksine, her birini elimle yazmaya kalkıştığımda sonuç hep daha kötü oldu.</p>
                    <p>Kullanmadığın bir paket varsa, denemek için bir günü ayır. Hangi sorunu çözdüğünü anlamadan, "şimdi lazım" demeden kurma.</p>
                </div>
            </div>
        </div>

        <footer class="article-footer">
            <div class="container-narrow">
                <div class="article-tags-row">
                    <div class="eyebrow-sm">Etiketler</div>
                    <div class="tag-list"><span class="chip">Laravel</span><span class="chip">PHP</span><span class="chip">Backend</span></div>
                </div>
                <div class="article-cta">
                    <h3>Bu yazıdan hoşlandın mı?</h3>
                    <p>Ayda 1-2 yazı. Email'ine düşüversin.</p>
                    <form data-mk-form>
                        <input class="input" name="email" placeholder="email@adres" />
                        <button type="submit" class="btn btn-primary">Abone ol →</button>
                    </form>
                </div>
            </div>
        </footer>
    </article>

    <section><div class="container">
        <div class="section-head"><h2>Sıradakiler</h2><a href="{{ route('blog') }}" class="link-arrow">Tüm yazılar →</a></div>
        <div class="grid-3">
            <a href="{{ route('blog.show', 'dotnet-api-hatalari') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">.NET Core ile API yazarken yaptığım 5 hata</h3></div><div class="card-desc">8 dk · .NET</div></a>
            <a href="{{ route('blog.show', 'vue-composition-api') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Vue 3 Composition API: pratiğe geçince</h3></div><div class="card-desc">5 dk · Vue</div></a>
            <a href="{{ route('blog.show', 'multi-tenant-saas') }}" class="card"><div class="card-image"></div><div class="card-title-row"><h3 class="card-title">Multi-tenant SaaS: tek veritabanı ya da kiracı başına?</h3></div><div class="card-desc">12 dk · Mimari</div></a>
        </div>
    </div></section>
@endsection
