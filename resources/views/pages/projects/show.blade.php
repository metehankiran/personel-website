@extends('layouts.app')

@section('title', $project->title . ' — Projeler')
@section('meta_description', \App\Support\Seo::description($project->description, $project->body))
@section('og_image', \App\Support\Images::og($project->cover_image))

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::project($project), \App\Support\Schema::breadcrumbs(['Projeler' => \App\Support\Seo::route('projects'), $project->title => \App\Support\Seo::route('projects.show', $project)])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">

            {{-- Back Link --}}
            <div class="mb-12">
                <a href="{{ route('projects') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors inline-flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Tüm projeler
                </a>
            </div>

            {{-- Hero --}}
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10 lg:gap-16 items-end">
                <div>
                    <div class="mb-3">
                        <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $project->category->name }}</span>
                    </div>
                    <h1 class="text-[44px] sm:text-[56px] lg:text-[72px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $project->title }}</h1>
                    <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[560px]">{{ $project->description }}</p>
                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">Benzer bir şey yap <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                    </div>
                </div>

                {{-- Definition Stack --}}
                <div class="flex flex-col gap-5 pb-2">
                    @if($project->client)
                        <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                            <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">Müşteri</span>
                            <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ $project->client }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                        <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">Yıl</span>
                        <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ $project->year }}</span>
                    </div>
                    @if($project->duration)
                        <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                            <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">Süre</span>
                            <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ $project->duration }}</span>
                        </div>
                    @endif
                    @if($project->role)
                        <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                            <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">Rolüm</span>
                            <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ $project->role }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                        <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">Stack</span>
                        <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ implode(' · ', $project->stack) }}</span>
                    </div>
                    @if($project->extras)
                        @foreach($project->extras as $extra)
                            <div class="flex justify-between border-b border-neutral-200 dark:border-neutral-800 pb-3">
                                <span class="text-xs text-neutral-500 tracking-[1.2px] uppercase">{{ $extra['label'] }}</span>
                                <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ $extra['value'] }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Cover + Stats --}}
            @if($project->cover_image && $project->stats && count($project->stats) <= 5)
                <div class="mt-16 grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-8 items-stretch">
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden">
                        <x-image :src="$project->cover_image" fallback="cover" :alt="$project->title" class="w-full h-full object-cover" />
                    </div>
                    <div class="flex flex-col gap-4 justify-center">
                        @foreach($project->stats as $stat)
                            <div class="p-7 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                                <div class="text-[44px] font-medium tracking-tighter leading-none text-neutral-950 dark:text-neutral-50">{{ $stat['value'] }}</div>
                                <div class="mt-2 text-[13px] text-neutral-600 dark:text-neutral-400">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($project->cover_image && $project->stats)
                <div class="mt-16 aspect-video rounded-2xl overflow-hidden">
                    <x-image :src="$project->cover_image" fallback="cover" :alt="$project->title" class="w-full h-full object-cover" />
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-8">
                    @foreach($project->stats as $stat)
                        <div class="p-7 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                            <div class="text-[44px] font-medium tracking-tighter leading-none text-neutral-950 dark:text-neutral-50">{{ $stat['value'] }}</div>
                            <div class="mt-2 text-[13px] text-neutral-600 dark:text-neutral-400">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            @elseif($project->cover_image)
                <div class="mt-16 aspect-video rounded-2xl overflow-hidden">
                    <x-image :src="$project->cover_image" fallback="cover" :alt="$project->title" class="w-full h-full object-cover" />
                </div>
            @elseif($project->stats)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-16">
                    @foreach($project->stats as $stat)
                        <div class="p-7 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                            <div class="text-[44px] font-medium tracking-tighter leading-none text-neutral-950 dark:text-neutral-50">{{ $stat['value'] }}</div>
                            <div class="mt-2 text-[13px] text-neutral-600 dark:text-neutral-400">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    {{-- Body --}}
    @if($project->body)
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-4 lg:gap-16 items-start">
                    <x-section-heading>Proje hakkında</x-section-heading>
                    <div class="flex flex-col gap-[18px] text-[17px] leading-[1.7] opacity-90 max-w-[760px] text-neutral-600 dark:text-neutral-400
                        [&>p]:m-0
                        [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:text-2xl [&>h2]:font-semibold [&>h2]:tracking-tight [&>h2]:text-neutral-950 dark:[&>h2]:text-neutral-50 [&>h2]:opacity-100
                        [&>ul]:pl-5 [&>ul]:list-disc
                        [&>ol]:pl-5 [&>ol]:list-decimal
                        [&>a]:underline [&>a]:underline-offset-2
                        [&>strong]:font-semibold">
                        {!! $project->body !!}
                    </div>
                    </div>
            </div>
        </section>
    @endif

    {{-- Related Projects --}}
    @if($relatedProjects->isNotEmpty())
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-baseline mb-7">
                    <h2 class="m-0 text-2xl font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Diğer projeler</h2>
                    <a href="{{ route('projects') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Tümünü gör →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($relatedProjects as $related)
                        <a href="{{ route('projects.show', $related) }}" class="block p-6 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <div class="aspect-[16/10] rounded-lg mb-4 overflow-hidden border border-neutral-200 dark:border-neutral-800">
                                <x-image :src="$related->cover_image" fallback="cover" :alt="$related->title" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex justify-between items-baseline mb-1.5">
                                <h3 class="m-0 text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $related->title }}</h3>
                                <span class="text-xs text-neutral-600 dark:text-neutral-400 tabular-nums">{{ $related->year }}</span>
                            </div>
                            <div class="text-[13px] text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $related->description }}</div>
                            <span class="inline-block mt-3 text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-800 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $related->category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
