@extends('layouts.app')

@section('title', 'Yer İşaretlerim — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Yer İşaretlerim</div>
        <h1 class="h1">Faydalı linkler.</h1>
        <p class="lede" style="max-width:560px;">Çalışırken sık geri döndüğüm, başkalarına da değerli olabileceğini düşündüğüm kaynaklar. Konuya göre gruplanmış.</p>

        <div style="margin-top:64px;">
            @foreach($categories as $category)
                <div class="stack-section">
                    <div class="stack-aside">
                        <h2>{{ $category->name }}</h2>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @endif
                    </div>
                    <div>
                        @foreach($category->bookmarks as $bookmark)
                            <a href="{{ $bookmark->url }}" class="uses-row" target="_blank" rel="noopener"><span class="uses-name">{{ $bookmark->domain }}</span><span class="uses-desc">{{ $bookmark->description }}</span></a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div></section>
@endsection
