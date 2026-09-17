@extends('layouts.app')

@section('full_title', $general->author_name . ' — ' . ($general->author_title ?: 'Developer'))

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
                    @php($heroTitle = \App\Support\InlineHtml::from($general->hero_title))
                    @if($heroTitle !== '')
                        <h1 class="text-[44px] sm:text-[56px] lg:text-[72px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0 [&_em]:italic [&_em]:font-normal">{!! $heroTitle !!}</h1>
                    @endif
                    @if($general->hero_subtitle)
                        <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-7 max-w-[540px]">{{ $general->hero_subtitle }}</p>
                    @endif
                    <div class="mt-8 flex gap-3">
                        <a href="{{ $link('contact') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 whitespace-nowrap">İletişime geç <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                        <a href="{{ $link('projects') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-transparent text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600 whitespace-nowrap">Projeleri gör</a>
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
                                    <span class="text-xs text-neutral-500 tabular-nums">{{ $post->published_at->translatedFormat('d M Y') }}</span>
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
            </div>

            <div class="group relative overflow-hidden" data-marquee>
                {{-- Edge fades: blurred + faded so cards dissolve at both sides --}}
                <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 z-10 w-24 sm:w-40 backdrop-blur-sm bg-gradient-to-r from-white via-white/70 to-transparent dark:from-neutral-950 dark:via-neutral-950/70 [mask-image:linear-gradient(to_right,black_30%,transparent)]"></div>
                <div aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 z-10 w-24 sm:w-40 backdrop-blur-sm bg-gradient-to-l from-white via-white/70 to-transparent dark:from-neutral-950 dark:via-neutral-950/70 [mask-image:linear-gradient(to_left,black_30%,transparent)]"></div>

                <div class="flex w-max gap-5 px-6 lg:px-12 animate-marquee motion-reduce:animate-none group-hover:[animation-play-state:paused]">
                    @foreach([0, 1] as $copy)
                        @foreach($testimonials as $testimonial)
                            <figure @if($copy === 1) aria-hidden="true" @endif class="m-0 w-[320px] sm:w-[380px] shrink-0 p-7 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 bg-white dark:bg-neutral-900 shadow-[0_1px_2px_rgba(0,0,0,0.04),0_12px_32px_-16px_rgba(0,0,0,0.12)] dark:shadow-none flex flex-col">
                                <blockquote class="m-0 flex-1 text-[15px] leading-relaxed text-neutral-800 dark:text-neutral-100">
                                    <span class="text-[48px] leading-none font-serif text-neutral-200 dark:text-neutral-700 float-left mr-2 -mt-1">"</span>
                                    {{ $testimonial->body }}
                                </blockquote>
                                <figcaption class="mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center gap-3">
                                    @if($testimonial->avatar)
                                        <x-image :src="$testimonial->avatar" fallback="avatar" :alt="$testimonial->name" class="w-10 h-10 rounded-full object-cover" width="40" height="40" />
                                    @else
                                        <span class="w-10 h-10 rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 text-sm font-semibold inline-flex items-center justify-center">{{ Str::of($testimonial->name)->substr(0, 1)->upper() }}</span>
                                    @endif
                                    <div>
                                        <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $testimonial->name }}</div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $testimonial->title }}{{ $testimonial->company ? ', '.$testimonial->company : '' }}</div>
                                    </div>
                                </figcaption>
                            </figure>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="pb-2">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="relative overflow-hidden rounded-2xl bg-neutral-950 text-white isolate" data-cta>
                {{-- Animated colour glows --}}
                <div aria-hidden="true" class="absolute -top-32 -left-24 w-[420px] h-[420px] rounded-full bg-violet-500/60 blur-3xl animate-cta-glow motion-reduce:animate-none"></div>
                <div aria-hidden="true" class="absolute -bottom-40 right-[10%] w-[460px] h-[460px] rounded-full bg-orange-400/50 blur-3xl animate-cta-glow [animation-delay:-4s] motion-reduce:animate-none"></div>
                <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[520px] h-[260px] rounded-full bg-sky-400/40 blur-3xl animate-cta-glow [animation-delay:-8s] motion-reduce:animate-none"></div>
                {{-- Dot grid + subtle noise so the gradient doesn't band --}}
                <div aria-hidden="true" class="absolute inset-0 opacity-[0.12]" style="background-image:radial-gradient(circle at 1px 1px, #fff 1px, transparent 0);background-size:22px 22px;"></div>
                <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-br from-neutral-950/40 via-transparent to-neutral-950/60"></div>

                <div class="relative px-8 sm:px-12 py-12 sm:py-16 flex flex-col sm:flex-row items-center justify-between gap-8">
                    <div class="text-left sm:flex-1">
                        <span class="inline-flex items-center gap-2 text-[11px] font-medium uppercase tracking-[1.6px] text-white/70 mb-4">
                            <span class="relative flex w-2 h-2">
                                <span class="absolute inline-flex w-full h-full rounded-full bg-emerald-400 opacity-75 animate-ping motion-reduce:animate-none"></span>
                                <span class="relative inline-flex w-2 h-2 rounded-full bg-emerald-400"></span>
                            </span>
                            Yeni projelere açığım
                        </span>
                        <h2 class="m-0 text-[30px] sm:text-[40px] font-medium tracking-tighter leading-[1.05] text-white text-balance">Bir proje fikrin var mı? <span class="text-white/60">Birlikte hayata geçirelim.</span></h2>
                        <p class="mt-3 text-[15px] text-white/70 leading-relaxed max-w-[480px]">Konuşalım. Genelde 24 saat içinde dönerim.</p>
                    </div>
                    <div class="flex gap-2.5 w-full sm:w-auto">
                        <a href="{{ $link('contact') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-semibold rounded-xl bg-white text-neutral-950 shadow-[0_8px_30px_-8px_rgba(255,255,255,0.5)] transition-transform hover:-translate-y-0.5 whitespace-nowrap">İletişime geç <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                        <a href="mailto:{{ $general->author_email ?? '' }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3.5 text-sm font-medium rounded-xl bg-white/10 backdrop-blur text-white border border-white/20 transition-colors hover:bg-white/20 whitespace-nowrap">
                            <i data-lucide="mail" class="w-4 h-4"></i> Email gönder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
