@extends('layouts.app')

@section('title', 'Blog — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="split-2" style="grid-template-columns:1fr 280px;align-items:start;">
            <div>
                <div class="eyebrow">Blog</div>
                <h1 class="h1">Yazılar.</h1>
                <p class="lede" style="max-width:480px;">Çalışırken karşılaştığım problemleri, deneyimleri ve denedikçe öğrendiklerimi yazıyorum.</p>
                <div style="margin-top:48px;">
                    @forelse($posts as $post)
                        <a href="{{ route('blog.show', $post) }}" class="post" data-filter-target="blog" data-filter-value="{{ $post->category->name }}">
                            <div class="post-meta">
                                <span class="chip">{{ $post->category->name }}</span>
                                <span class="post-date">{{ $post->published_at->format('Y-m-d') }}</span>
                                <span class="post-read">· {{ $post->reading_time }} dk okuma</span>
                            </div>
                            <h2>{{ $post->title }}</h2>
                            @if($post->excerpt)
                                <p class="post-excerpt">{{ $post->excerpt }}</p>
                            @endif
                        </a>
                    @empty
                        <p style="color:var(--dim);">Henüz yazı yayınlanmadı.</p>
                    @endforelse
                </div>
            </div>
            <aside class="sidebar" style="display:flex;flex-direction:column;gap:32px;">
                @if($tags->isNotEmpty())
                    <div>
                        <div class="eyebrow-sm" style="margin-bottom:16px;">Etiketler</div>
                        <div class="tag-list" data-filter-group="blog">
                            <button class="tag active" data-filter="all">Hepsi</button>
                            @foreach($tags as $tag)
                                <button class="tag" data-filter="{{ $tag->name }}">{{ $tag->name }}</button>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="sidebar-card" id="newsletter">
                    <div style="font-size:14px;font-weight:600;margin-bottom:6px;">Yeni yazılar için bülten</div>
                    <p style="margin:0 0 12px;font-size:12px;color:var(--dim);line-height:1.5;">Ayda 1-2 yazı, spam yok.</p>
                    @if(session('success'))
                        <p style="font-size:13px;color:var(--success);">{{ session('success') }}</p>
                    @endif
                    <form action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input class="input input-sm" name="email" type="email" placeholder="email@adres" required style="margin-bottom:8px;" />
                        @error('email')
                            <p style="font-size:12px;color:red;margin:0 0 8px;">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="btn btn-primary btn-sm btn-block">Abone ol</button>
                    </form>
                </div>
            </aside>
        </div>
    </div></section>
@endsection
