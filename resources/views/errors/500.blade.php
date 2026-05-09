@extends('layouts.app')

@section('title', '500 — Sunucuda bir hata')

@section('content')
    <section style="padding-top:80px;padding-bottom:80px;">
        <div class="container-narrow" style="text-align:center;">
            <div class="error-code">500</div>
            <h1 class="h1-xl" style="margin:24px 0 0;">Sunucuda bir şey patladı.</h1>
            <p class="lede" style="margin:24px auto 0;max-width:520px;">Suç bende, sende değil. Birkaç saniye sonra tekrar dene — değilse ekibe haber ediyorum.</p>
            <div style="margin-top:40px;display:inline-flex;gap:12px;flex-wrap:wrap;justify-content:center;">
                <button type="button" class="btn btn-primary" onclick="window.location.reload()">Tekrar dene</button>
                <a href="{{ route('home') }}" class="btn btn-secondary">Ana sayfa</a>
            </div>
            <div style="margin-top:48px;font-size:13px;color:var(--sub);">
                Hata kodu: <span class="mono" style="font-size:13px;">500 · INTERNAL_ERROR</span><br/>
                Sürekli oluyorsa: <a href="mailto:merhaba@metehankiran.dev" style="color:var(--fg);text-decoration:underline;">merhaba@metehankiran.dev</a>
            </div>
        </div>
    </section>
@endsection
