@extends('layouts.app')

@section('title', $post->title . ' — Metehan Kıran')

@section('content')
    <article class="article">
        <header class="article-header">
            <div class="container-narrow">
                <div class="article-meta-top">
                    <a href="{{ route('blog') }}" class="back-link">← Blog</a>
                    <span class="chip">{{ $post->category->name }}</span>
                </div>
                <h1 class="article-title">{{ $post->title }}</h1>
                @if($post->excerpt)
                    <p class="article-lede">{{ $post->excerpt }}</p>
                @endif
                <div class="article-byline">
                    <div class="byline-author">
                        <span class="brand-mark"></span>
                        <div>
                            <div class="byline-name">Metehan Kıran</div>
                            <div class="byline-meta">{{ $post->published_at->translatedFormat('d F Y') }} · {{ $post->reading_time }} dk okuma</div>
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

        @if($post->cover_image)
            <div class="container-narrow" style="margin-bottom:48px;">
                <div style="aspect-ratio:16/9;border-radius:12px;overflow:hidden;">
                    <img src="{{ Storage::url($post->cover_image) }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
            </div>
        @endif

        <div class="container-narrow article-body">
            <div class="prose-article">
                {!! $post->body !!}
            </div>
        </div>

        <footer class="article-footer">
            <div class="container-narrow">
                @if($post->tags->isNotEmpty())
                    <div class="article-tags-row">
                        <div class="eyebrow-sm">Etiketler</div>
                        <div class="tag-list">
                            @foreach($post->tags as $tag)
                                <span class="chip">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="article-cta" id="newsletter">
                    <h3>Bu yazıdan hoşlandın mı?</h3>
                    <p>Ayda 1-2 yazı. Email'ine düşüversin.</p>
                    @if(session('success'))
                        <p style="font-size:13px;color:var(--success);">{{ session('success') }}</p>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input class="input" name="email" type="email" placeholder="email@adres" required />
                        @error('email')
                            <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn btn-primary">Abone ol →</button>
                    </form>
                </div>
            </div>
        </footer>
    </article>

    @if($relatedPosts->isNotEmpty())
        <section><div class="container">
            <div class="section-head"><h2>Sıradakiler</h2><a href="{{ route('blog') }}" class="link-arrow">Tüm yazılar →</a></div>
            <div class="grid-3">
                @foreach($relatedPosts as $related)
                    <a href="{{ route('blog.show', $related) }}" class="card">
                        <div class="card-image">
                            @if($related->cover_image)
                                <img src="{{ Storage::url($related->cover_image) }}" alt="{{ $related->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                            @endif
                        </div>
                        <div class="card-title-row"><h3 class="card-title">{{ $related->title }}</h3></div>
                        <div class="card-desc">{{ $related->reading_time }} dk · {{ $related->category->name }}</div>
                    </a>
                @endforeach
            </div>
        </div></section>
    @endif
@endsection
