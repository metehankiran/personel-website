@extends('layouts.app')

@section('title', 'Blog — Metehan Kıran')

@php
    $post = fn (string $slug): string => Route::has('blog.show') ? route('blog.show', $slug) : '#';
@endphp

@section('content')
    <section><div class="container">
        <div class="split-2" style="grid-template-columns:1fr 280px;align-items:start;">
            <div>
                <div class="eyebrow">Blog</div>
                <h1 class="h1">Yazılar.</h1>
                <p class="lede" style="max-width:480px;">Çalışırken karşılaştığım problemleri, deneyimleri ve denedikçe öğrendiklerimi yazıyorum.</p>
                <div style="margin-top:48px;">
                    <a href="{{ $post('laravel-paketleri') }}" class="post" data-filter-target="blog" data-filter-value="Laravel">
                        <div class="post-meta"><span class="chip">Laravel</span><span class="post-date">2024-11-12</span><span class="post-read">· 6 dk okuma</span></div>
                        <h2>Laravel'de gerçekten lazım olan paketler</h2>
                        <p class="post-excerpt">Her projede tekrar tekrar kurduğum 7 paket ve neden vazgeçilmez olduklarını anlatıyorum.</p>
                    </a>
                    <a href="{{ $post('dotnet-api-hatalari') }}" class="post" data-filter-target="blog" data-filter-value=".NET">
                        <div class="post-meta"><span class="chip">.NET</span><span class="post-date">2024-10-28</span><span class="post-read">· 8 dk okuma</span></div>
                        <h2>.NET Core ile API yazarken yaptığım 5 hata</h2>
                        <p class="post-excerpt">Laravel arka planından gelirken .NET Core'da çarptığım duvarlar — ve çözümleri.</p>
                    </a>
                    <a href="{{ $post('vue-composition-api') }}" class="post" data-filter-target="blog" data-filter-value="Vue">
                        <div class="post-meta"><span class="chip">Vue</span><span class="post-date">2024-09-15</span><span class="post-read">· 5 dk okuma</span></div>
                        <h2>Vue 3 Composition API: pratiğe geçince</h2>
                        <p class="post-excerpt">Options API'den geçtim, bir yıldır Composition kullanıyorum. Notlar.</p>
                    </a>
                    <a href="{{ $post('multi-tenant-saas') }}" class="post" data-filter-target="blog" data-filter-value="Mimari">
                        <div class="post-meta"><span class="chip">Mimari</span><span class="post-date">2024-08-04</span><span class="post-read">· 12 dk okuma</span></div>
                        <h2>Multi-tenant SaaS: tek veritabanı ya da kiracı başına?</h2>
                        <p class="post-excerpt">Karavela'da verdiğimiz kararı, bugün baştan verseydim ne yapardım yazısı.</p>
                    </a>
                    <a href="{{ $post('freelance-ilk-yil') }}" class="post" data-filter-target="blog" data-filter-value="Freelance">
                        <div class="post-meta"><span class="chip">Freelance</span><span class="post-date">2024-07-22</span><span class="post-read">· 10 dk okuma</span></div>
                        <h2>Freelance ilk yılım — açık rakamlarla</h2>
                        <p class="post-excerpt">12 ay, 18 müşteri, 4 hafta tatil. Detaylar içeride.</p>
                    </a>
                    <a href="{{ $post('production-docker') }}" class="post" data-filter-target="blog" data-filter-value="DevOps">
                        <div class="post-meta"><span class="chip">DevOps</span><span class="post-date">2024-06-10</span><span class="post-read">· 7 dk okuma</span></div>
                        <h2>Production'da Docker: faydalı 6 pattern</h2>
                        <p class="post-excerpt">Geliştirme ortamı dışında Docker'ı production'da nasıl kullanıyorum.</p>
                    </a>
                    <a href="{{ $post('postgres-explain-analyze') }}" class="post" data-filter-target="blog" data-filter-value="Postgres">
                        <div class="post-meta"><span class="chip">Postgres</span><span class="post-date">2024-05-18</span><span class="post-read">· 9 dk okuma</span></div>
                        <h2>Postgres'te N+1 ve EXPLAIN ANALYZE</h2>
                        <p class="post-excerpt">Performans sorunlarını okumayı öğrenmek hayatımı değiştirdi.</p>
                    </a>
                    <a href="{{ $post('typescript-gecis') }}" class="post" data-filter-target="blog" data-filter-value="TS">
                        <div class="post-meta"><span class="chip">TS</span><span class="post-date">2024-04-02</span><span class="post-read">· 6 dk okuma</span></div>
                        <h2>TypeScript'e geçişe değer mi?</h2>
                        <p class="post-excerpt">Vue projesi için yaptığım denemeler ve vardığım sonuç.</p>
                    </a>
                </div>
            </div>
            <aside class="sidebar" style="display:flex;flex-direction:column;gap:32px;">
                <div>
                    <div class="eyebrow-sm" style="margin-bottom:16px;">Etiketler</div>
                    <div class="tag-list" data-filter-group="blog">
                        <button class="tag active" data-filter="all">Hepsi</button>
                        <button class="tag" data-filter="Laravel">Laravel</button>
                        <button class="tag" data-filter=".NET">.NET</button>
                        <button class="tag" data-filter="Vue">Vue</button>
                        <button class="tag" data-filter="Mimari">Mimari</button>
                        <button class="tag" data-filter="Freelance">Freelance</button>
                        <button class="tag" data-filter="DevOps">DevOps</button>
                        <button class="tag" data-filter="Postgres">Postgres</button>
                        <button class="tag" data-filter="TS">TS</button>
                    </div>
                </div>
                <div class="sidebar-card">
                    <div style="font-size:14px;font-weight:600;margin-bottom:6px;">Yeni yazılar için bülten</div>
                    <p style="margin:0 0 12px;font-size:12px;color:var(--dim);line-height:1.5;">Ayda 1-2 yazı, spam yok.</p>
                    <form data-mk-form>
                        <input class="input input-sm" name="email" placeholder="email@adres" style="margin-bottom:8px;" />
                        <button type="submit" class="btn btn-primary btn-sm btn-block">Abone ol</button>
                    </form>
                </div>
            </aside>
        </div>
    </div></section>
@endsection
