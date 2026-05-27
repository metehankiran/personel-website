@extends('layouts.app')

@section('title', $general->author_name . ' — ' . ($general->author_title ?? 'Developer'))

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@section('content')
    {{-- Hero --}}
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-10 lg:gap-12 items-start">
                <div>
                    @if($general->availability_status)
                        <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                {{ $general->availability_status }}
                            </span>
                        </div>
                    @endif
                    @if($general->hero_title)
                        <h1 class="text-[44px] sm:text-[56px] lg:text-[72px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0 [&>em]:italic [&>em]:font-normal">{!! $general->hero_title !!}</h1>
                    @endif
                    @if($general->hero_subtitle)
                        <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-7 max-w-[540px]">{{ $general->hero_subtitle }}</p>
                    @endif
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ $link('contact') }}" class="inline-flex items-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">İletişime geç <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                        <a href="{{ $link('projects') }}" class="inline-flex items-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-transparent text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">Projeleri gör</a>
                    </div>
                </div>

                {{-- Sağ: Profil kartı --}}
                <div class="relative overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
                    {{-- Üst: profil bölümü --}}
                    <div class="p-6 pb-5 flex items-center gap-4 border-b border-neutral-200 dark:border-neutral-800">
                        <span class="w-12 h-12 rounded-full bg-neutral-950 dark:bg-neutral-50 relative inline-block shrink-0">
                            <span class="absolute inset-[8px] bg-white dark:bg-neutral-950 rounded-full"></span>
                        </span>
                        <div>
                            <div class="text-[15px] font-semibold text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</div>
                            <div class="text-xs text-neutral-500 mt-0.5">{{ $general->author_title ?? 'Developer' }} · {{ $general->author_location ?? '' }}</div>
                        </div>
                    </div>

                    {{-- Orta: stat grid --}}
                    @if($general->homepage_stats)
                        <div class="grid grid-cols-2 gap-px bg-neutral-200 dark:bg-neutral-800">
                            @foreach($general->homepage_stats as $stat)
                                <div class="bg-neutral-50 dark:bg-neutral-900 p-5">
                                    <div class="text-[11px] text-neutral-500 tracking-[1px] uppercase mb-1.5">{{ $stat['label'] }}</div>
                                    <div class="text-lg font-semibold text-neutral-950 dark:text-neutral-50 tracking-tight">{{ $stat['value'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Alt: hızlı linkler --}}
                    <div class="p-4 border-t border-neutral-200 dark:border-neutral-800 flex gap-2">
                        <a href="{{ $link('cv') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 text-neutral-950 dark:text-neutral-50 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <i data-lucide="download" class="w-3 h-3"></i> CV
                        </a>
                        <a href="mailto:{{ $general->author_email ?? '' }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 text-neutral-950 dark:text-neutral-50 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <i data-lucide="mail" class="w-3 h-3"></i> Email
                        </a>
                        <a href="{{ $link('stack') }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg bg-white dark:bg-neutral-950 border border-neutral-200 dark:border-neutral-800 text-neutral-950 dark:text-neutral-50 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <i data-lucide="layers" class="w-3 h-3"></i> Stack
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Projects --}}
    @if($projects->isNotEmpty())
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-baseline mb-7">
                    <h2 class="m-0 text-2xl font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Projeler</h2>
                    <a href="{{ $link('projects') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Tümünü gör →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($projects as $project)
                        <a href="{{ route('projects.show', $project) }}" class="block p-6 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            @if($project->cover_image)
                                <div class="aspect-[16/10] rounded-lg mb-4 overflow-hidden border border-neutral-200 dark:border-neutral-800">
                                    <img src="{{ Storage::url($project->cover_image) }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="aspect-[16/10] rounded-lg mb-4 bg-gradient-to-br from-neutral-200 dark:from-neutral-800 to-neutral-100 dark:to-neutral-900 border border-neutral-200 dark:border-neutral-800"></div>
                            @endif
                            <div class="flex justify-between items-baseline mb-1.5">
                                <h3 class="m-0 text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $project->title }}</h3>
                                <span class="text-xs text-neutral-600 dark:text-neutral-400 tabular-nums">{{ $project->year }}</span>
                            </div>
                            <div class="text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed mb-3">{{ $project->description }}</div>
                            <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $project->category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-baseline mb-7">
                    <h2 class="m-0 text-2xl font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Son Yazılar</h2>
                    <a href="{{ $link('blog') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Tümünü gör →</a>
                </div>
                <div>
                    @foreach($posts as $post)
                        <a href="{{ route('blog.show', $post) }}" class="block py-6 border-b border-neutral-200 dark:border-neutral-800 first:border-t transition-colors hover:opacity-80">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-2 sm:gap-8">
                                <h3 class="m-0 text-lg font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $post->title }}</h3>
                                <div class="flex items-center gap-3 shrink-0">
                                    <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $post->category->name }}</span>
                                    <span class="text-xs text-neutral-500 tabular-nums">{{ $post->published_at->format('d M Y') }}</span>
                                </div>
                            </div>
                            @if($post->excerpt)
                                <p class="m-0 mt-2 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-[640px]">{{ $post->excerpt }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Testimonials --}}
    @if($testimonials->isNotEmpty())
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-baseline mb-7">
                    <h2 class="m-0 text-2xl font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Müşteri Yorumları</h2>
                    <a href="{{ $link('references') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Tümünü gör →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($testimonials as $testimonial)
                        <div class="p-7 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 flex flex-col">
                            <p class="m-0 flex-1 text-[15px] leading-relaxed opacity-95 text-neutral-950 dark:text-neutral-50">
                                <span class="text-[48px] leading-none font-serif text-neutral-200 dark:text-neutral-800 float-left mr-2 -mt-1">"</span>
                                {{ $testimonial->body }}
                            </p>
                            <div class="mt-6 pt-4 border-t border-neutral-200 dark:border-neutral-800 flex items-center gap-3">
                                @if($testimonial->avatar)
                                    <img src="{{ Storage::url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-10 h-10 rounded-full object-cover" width="40" height="40">
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $testimonial->name }}</div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="pb-2">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="relative overflow-hidden rounded-2xl bg-neutral-950 dark:bg-neutral-50 text-center">
                <div class="absolute inset-0 opacity-[0.07]" style="background-image:radial-gradient(circle at 1px 1px, currentColor 1px, transparent 0);background-size:24px 24px;"></div>
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[150px] bg-gradient-to-b from-white/10 dark:from-neutral-950/10 to-transparent rounded-full blur-3xl"></div>
                <div class="relative px-8 sm:px-12 py-10 sm:py-12 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="text-left sm:flex-1">
                        <h2 class="m-0 text-[26px] sm:text-[32px] font-medium tracking-tighter leading-[1.1] text-white dark:text-neutral-950">Bir proje fikrin var mı?</h2>
                        <p class="mt-2 text-sm text-neutral-400 dark:text-neutral-500 leading-relaxed">Konuşalım. Genelde 24 saat içinde dönerim.</p>
                    </div>
                    <div class="flex flex-wrap gap-2.5 shrink-0">
                        <a href="{{ $link('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 transition-opacity hover:opacity-90">İletişime geç <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                        <a href="mailto:{{ $general->author_email ?? '' }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl bg-transparent text-white dark:text-neutral-950 border border-white/20 dark:border-neutral-950/20 transition-colors hover:bg-white/10 dark:hover:bg-neutral-950/10">
                            <i data-lucide="mail" class="w-4 h-4"></i> Email gönder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
