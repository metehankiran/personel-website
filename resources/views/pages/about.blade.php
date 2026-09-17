@extends('layouts.app')

@section('title', 'Hakkımda: Full Stack Web Developer')
@section('meta_description', \App\Support\Seo::description($about->body, $general->bio, $general->author_name.' hakkında: geçmişi, çalışma şekli ve bugüne kadar yaptığı işlerin zaman çizelgesi.'))

@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::profilePage(), \App\Support\Schema::breadcrumbs(['Hakkımda' => \App\Support\Seo::route('about')])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-10 lg:gap-16 items-start">

                {{-- Main Content --}}
                <div>
                    <x-eyebrow class="mb-3">Hakkımda</x-eyebrow>
                    @if(filled($about->heading))
                        <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $about->heading }}</h1>
                    @endif

                    @if(filled($about->body))
                        <div data-about-body class="flex flex-col gap-[18px] text-[17px] leading-[1.7] opacity-90 max-w-[640px] mt-8 text-neutral-950 dark:text-neutral-50 [&_p]:m-0 [&_em]:italic [&_em]:font-normal [&_a]:underline [&_a]:underline-offset-2 [&_ul]:m-0 [&_ul]:pl-5 [&_ul]:list-disc [&_ol]:m-0 [&_ol]:pl-5 [&_ol]:list-decimal">
                            {!! $about->body !!}
                        </div>
                    @endif

                    {{-- Timeline --}}
                    @if($timeline->isNotEmpty())
                        <div class="mt-16">
                            <x-section-heading class="mb-6">Zaman çizelgesi</x-section-heading>

                            @foreach($timeline as $entry)
                                <div @class(['grid grid-cols-[80px_200px_1fr] max-sm:grid-cols-[70px_1fr] gap-x-6 py-5 border-b border-neutral-200 dark:border-neutral-800 items-baseline', 'border-t' => $loop->first])>
                                    <span class="text-[13px] text-neutral-500 tabular-nums">{{ $entry->period }}</span>
                                    <span class="text-[15px] font-semibold text-neutral-950 dark:text-neutral-50">{{ $entry->title }}</span>
                                    @if($entry->description)
                                        <span class="text-sm text-neutral-600 dark:text-neutral-400 max-sm:col-span-full">{{ $entry->description }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="flex flex-col gap-6 lg:sticky lg:top-24">
                    @if(\App\Support\Images::exists($about->portrait_path))
                        <img data-about-portrait src="{{ \App\Support\Images::url($about->portrait_path) }}" alt="{{ $general->author_name }}" width="720" height="900" class="w-full aspect-[4/5] object-cover rounded-xl border border-neutral-200 dark:border-neutral-800" />
                    @endif

                    <div class="p-6 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                        <x-eyebrow size="sm" class="mb-4">Hızlı bilgiler</x-eyebrow>
                        <div class="flex flex-col gap-3 text-[13px]">
                            @if($general->author_location)
                                <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                    <span class="text-neutral-500">Lokasyon</span>
                                    <span class="font-medium text-neutral-950 dark:text-neutral-50">{{ $general->author_location }}</span>
                                </div>
                            @endif
                            @if(filled($about->work_mode))
                                <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                    <span class="text-neutral-500">Çalışma şekli</span>
                                    <span class="font-medium text-neutral-950 dark:text-neutral-50">{{ $about->work_mode }}</span>
                                </div>
                            @endif
                            @if($languages->isNotEmpty())
                                <div class="flex justify-between items-center gap-4 border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                    <span class="text-neutral-500">Diller</span>
                                    <span class="font-medium text-right text-neutral-950 dark:text-neutral-50">{{ $languages->join(' · ') }}</span>
                                </div>
                            @endif
                            @if($general->homepage_stats)
                                @foreach(collect($general->homepage_stats)->whereIn('label', ['Tecrübe']) as $stat)
                                    <div class="flex justify-between items-center border-b border-neutral-200 dark:border-neutral-800 pb-2.5">
                                        <span class="text-neutral-500">{{ $stat['label'] }}</span>
                                        <span class="font-medium text-neutral-950 dark:text-neutral-50">{{ $stat['value'] }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="mt-5 flex gap-2">
                            <a href="{{ $link('cv') }}" data-cv-view class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-[13px] font-medium rounded-lg border border-neutral-200 dark:border-neutral-700 text-neutral-950 dark:text-neutral-50 transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800 whitespace-nowrap">
                                <i data-lucide="file-text" class="w-3.5 h-3.5"></i> CV görüntüle
                            </a>
                            @if($general->cvUrl())
                                <a href="{{ $general->cvUrl() }}" data-cv-download download="{{ $general->cvDownloadName() }}" class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 whitespace-nowrap">
                                    <i data-lucide="download" class="w-3.5 h-3.5"></i> CV indir
                                </a>
                            @endif
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>
@endsection
