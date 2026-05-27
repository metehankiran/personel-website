@extends('layouts.app')

@section('title', $project->title . ' — Projeler — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div style="margin-bottom:48px;"><a href="{{ route('projects') }}" class="back-link">← Tüm projeler</a></div>
        <div class="split-2 split-hero">
            <div>
                <div class="eyebrow"><span class="chip">{{ $project->category->name }}</span></div>
                <h1 class="h1-xl">{{ $project->title }}</h1>
                <p class="lede" style="max-width:560px;">{{ $project->description }}</p>
                <div style="margin-top:28px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('contact') }}" class="btn btn-primary">Benzer bir şey yap →</a>
                </div>
            </div>
            <div class="def-stack">
                @if($project->client)
                    <div class="def-row"><span class="def-key">Müşteri</span><span class="def-val">{{ $project->client }}</span></div>
                @endif
                <div class="def-row"><span class="def-key">Yıl</span><span class="def-val">{{ $project->year }}</span></div>
                @if($project->duration)
                    <div class="def-row"><span class="def-key">Süre</span><span class="def-val">{{ $project->duration }}</span></div>
                @endif
                @if($project->role)
                    <div class="def-row"><span class="def-key">Rolüm</span><span class="def-val">{{ $project->role }}</span></div>
                @endif
                <div class="def-row"><span class="def-key">Stack</span><span class="def-val">{{ implode(' · ', $project->stack) }}</span></div>
                @if($project->extras)
                    @foreach($project->extras as $extra)
                        <div class="def-row"><span class="def-key">{{ $extra['label'] }}</span><span class="def-val">{{ $extra['value'] }}</span></div>
                    @endforeach
                @endif
            </div>
        </div>
        @if($project->cover_image && $project->stats && count($project->stats) <= 5)
            <div style="margin-top:64px;display:grid;grid-template-columns:1.5fr 1fr;gap:32px;align-items:stretch;">
                <div class="featured-image" style="aspect-ratio:16/10;border-radius:16px;overflow:hidden;">
                    <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;">
                </div>
                <div style="display:flex;flex-direction:column;gap:16px;justify-content:center;">
                    @foreach($project->stats as $stat)
                        <div class="metric-card"><div class="metric-num">{{ $stat['value'] }}</div><div class="metric-label">{{ $stat['label'] }}</div></div>
                    @endforeach
                </div>
            </div>
        @elseif($project->cover_image && $project->stats)
            <div class="featured-image" style="margin-top:64px;aspect-ratio:16/9;border-radius:16px;overflow:hidden;">
                <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div class="grid-2 metrics-row" style="margin-top:32px;">
                @foreach($project->stats as $stat)
                    <div class="metric-card"><div class="metric-num">{{ $stat['value'] }}</div><div class="metric-label">{{ $stat['label'] }}</div></div>
                @endforeach
            </div>
        @elseif($project->cover_image)
            <div class="featured-image" style="margin-top:64px;aspect-ratio:16/9;border-radius:16px;overflow:hidden;">
                <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;">
            </div>
        @elseif($project->stats)
            <div class="grid-3 metrics-row" style="margin-top:64px;">
                @foreach($project->stats as $stat)
                    <div class="metric-card"><div class="metric-num">{{ $stat['value'] }}</div><div class="metric-label">{{ $stat['label'] }}</div></div>
                @endforeach
            </div>
        @endif
    </div></section>

    @if($project->body)
        <section><div class="container-narrow">
            <div class="prose">{!! $project->body !!}</div>
        </div></section>
    @endif

    @if($relatedProjects->isNotEmpty())
        <section><div class="container">
            <div class="section-head"><h2>Diğer projeler</h2><a href="{{ route('projects') }}" class="link-arrow">Tümünü gör →</a></div>
            <div class="grid-3">
                @foreach($relatedProjects as $related)
                    <a href="{{ route('projects.show', $related) }}" class="card">
                        <div class="card-image">
                            @if($related->cover_image)
                                <img src="{{ Storage::url($related->cover_image) }}" alt="{{ $related->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                            @endif
                        </div>
                        <div class="card-title-row"><h3 class="card-title">{{ $related->title }}</h3><span class="card-year">{{ $related->year }}</span></div>
                        <div class="card-desc">{{ $related->description }}</div>
                        <span class="chip">{{ $related->category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div></section>
    @endif
@endsection
