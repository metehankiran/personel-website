@extends('layouts.app')

@section('title', '404 — Sayfa kayıp')

@section('content')
    <section style="padding-top:80px;padding-bottom:80px;">
        <div class="container-narrow" style="text-align:center;">
            <div class="error-code">404</div>
            <h1 class="h1-xl" style="margin:24px 0 0;">Sayfa kayıp.</h1>
            <p class="lede" style="margin:24px auto 0;max-width:480px;">Yanlış bir URL'ye geldin ya da ben taşımış olabilirim. Önemli olan iyiyiz.</p>
            <div style="margin-top:40px;display:inline-flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                <a href="{{ route('home') }}" class="btn btn-primary">Ana sayfaya dön →</a>
                <button type="button" class="btn btn-secondary" data-search-trigger>Ara… <kbd style="margin-left:8px;font-size:11px;padding:2px 6px;border-radius:4px;background:var(--chip);">⌘K</kbd></button>
            </div>
            <div style="margin-top:64px;padding-top:32px;border-top:1px solid var(--border);">
                <div class="eyebrow" style="margin-bottom:20px;">Belki bunlardan birini arıyordun</div>
                <div class="grid-3" style="text-align:left;">
                    <a href="{{ route('projects') }}" class="card"><h3 class="card-title">Projeler</h3><div class="card-desc">5 yıllık seçili işler</div></a>
                    <a href="{{ route('blog') }}" class="card"><h3 class="card-title">Blog</h3><div class="card-desc">Teknik yazılar</div></a>
                    <a href="{{ route('contact') }}" class="card"><h3 class="card-title">İletişim</h3><div class="card-desc">Hızlı ulaş</div></a>
                </div>
            </div>
        </div>
    </section>
@endsection
