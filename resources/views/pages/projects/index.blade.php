@extends('layouts.app')

@section('title', 'Projeler — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="eyebrow">Projeler</div>
        <h1 class="h1">Yaptığım işler.</h1>
        <p class="lede">5 yıllık freelance kariyerimden seçili çalışmalar. Hepsi production'da, büyük çoğunluğu hâlâ canlı.</p>
        <div class="filters" data-filter-group="proj">
            <button class="chip-lg active" data-filter="all">Hepsi ({{ $projects->count() }})</button>
            @foreach($categories as $category)
                <button class="chip-lg" data-filter="{{ $category->name }}">{{ $category->name }} ({{ $projects->where('category_id', $category->id)->count() }})</button>
            @endforeach
        </div>
        <div class="grid-3" style="margin-top:48px;">
            @foreach($projects as $project)
                <a href="{{ route('projects.show', $project) }}" class="card {{ $loop->first ? 'card-wide' : '' }}" data-filter-target="proj" data-filter-value="{{ $project->category->name }}">
                    <div class="card-image">
                        @if($project->cover_image)
                            <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                        @endif
                    </div>
                    <div class="card-title-row"><h3 class="card-title">{{ $project->title }}</h3><span class="card-year">{{ $project->year }}</span></div>
                    <div class="card-desc">{{ $project->description }}</div>
                    <div class="card-meta"><span class="chip">{{ $project->category->name }}</span><span class="mono">{{ implode(' · ', $project->stack) }}</span></div>
                </a>
            @endforeach
        </div>
    </div></section>
@endsection
