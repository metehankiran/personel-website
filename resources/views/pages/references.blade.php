@extends('layouts.app')

@section('title', 'Referanslar — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Referanslar</div>
        <h1 class="h1">Birlikte çalıştığım insanlar.</h1>
        <p class="lede">40+ müşteriden bir kesit. Hepsi gerçek, isim+rol kontrol edilebilir.</p>
        <div class="grid-3" style="margin-top:56px;">
            @foreach($testimonials as $testimonial)
                <div class="quote-card {{ $loop->first ? 'big' : '' }}">
                    <p class="quote-text">{{ $testimonial->body }}</p>
                    <div class="quote-author">
                        @if($testimonial->avatar)
                            <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="quote-avatar" width="40" height="40" style="border-radius:50%;">
                        @endif
                        <div>
                            <div class="quote-name">{{ $testimonial->name }}</div>
                            <div class="quote-role">{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($brands->isNotEmpty())
            <div style="margin-top:80px;">
                <div class="eyebrow">Birlikte çalıştığım markalar</div>
                <div class="logo-grid">
                    @foreach($brands as $brand)
                        @if($brand->url)
                            <a href="{{ $brand->url }}" class="logo-cell" target="_blank" rel="noopener" style="text-decoration:none;color:inherit;">
                        @else
                            <div class="logo-cell">
                        @endif
                            @if($brand->logo)
                                <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ $brand->name }}
                            @endif
                        @if($brand->url)
                            </a>
                        @else
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div></section>
@endsection
