@extends('layouts.app')

@section('title', 'Projeler — Metehan Kıran')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Projeler</div>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Yaptığım işler.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">5 yıllık freelance kariyerimden seçili çalışmalar. Hepsi production'da, büyük çoğunluğu hâlâ canlı.</p>

            {{-- Filters --}}
            <div class="flex flex-wrap gap-2 mt-8" data-filter-group="proj">
                <button class="text-[13px] px-3.5 py-2 rounded-full font-medium border cursor-pointer font-sans transition-colors bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 border-neutral-950 dark:border-neutral-50" data-filter="all">Hepsi ({{ $projects->count() }})</button>
                @foreach($categories as $category)
                    <button class="text-[13px] px-3.5 py-2 rounded-full font-medium border cursor-pointer font-sans transition-colors bg-transparent text-neutral-600 dark:text-neutral-400 border-neutral-200 dark:border-neutral-800 hover:border-neutral-400 dark:hover:border-neutral-600" data-filter="{{ $category->name }}">{{ $category->name }} ({{ $projects->where('category_id', $category->id)->count() }})</button>
                @endforeach
            </div>

            {{-- Project Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-12">
                @foreach($projects as $project)
                    <a href="{{ route('projects.show', $project) }}" class="block p-6 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 {{ $loop->first ? 'sm:col-span-2' : '' }}" data-filter-target="proj" data-filter-value="{{ $project->category->name }}">
                        @if($project->cover_image)
                            <div class="{{ $loop->first ? 'aspect-[16/7]' : 'aspect-[16/10]' }} rounded-lg mb-4 overflow-hidden border border-neutral-200 dark:border-neutral-800">
                                <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover" loading="lazy">
                            </div>
                        @else
                            <div class="{{ $loop->first ? 'aspect-[16/7]' : 'aspect-[16/10]' }} rounded-lg mb-4 bg-gradient-to-br from-neutral-200 dark:from-neutral-800 to-neutral-100 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-800"></div>
                        @endif
                        <div class="flex justify-between items-baseline mb-1.5">
                            <h3 class="m-0 {{ $loop->first ? 'text-[22px]' : 'text-base' }} font-semibold text-neutral-950 dark:text-neutral-50">{{ $project->title }}</h3>
                            <span class="text-xs text-neutral-600 dark:text-neutral-400 tabular-nums">{{ $project->year }}</span>
                        </div>
                        <div class="text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed mb-3">{{ $project->description }}</div>
                        <div class="flex justify-between items-center gap-1.5">
                            <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $project->category->name }}</span>
                            <span class="font-mono text-[11px] text-neutral-500">{{ implode(' · ', $project->stack) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
