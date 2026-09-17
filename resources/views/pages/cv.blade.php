@extends('layouts.app')

@section('title', 'CV')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-[920px] mx-auto px-6 lg:px-12">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 sm:gap-12 mb-14">
                <div>
                    <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">CV</div>
                    <h1 class="text-[38px] lg:text-[56px] leading-none font-medium tracking-tighter text-neutral-950 dark:text-neutral-50 m-0">{{ $general->author_name }}</h1>
                    <p class="mt-3 text-lg text-neutral-600 dark:text-neutral-400 m-0">{{ $general->author_title ?? 'Developer' }} · {{ $general->author_location ?? '' }}</p>
                </div>
                @if($cvPath)
                    <a href="{{ Storage::url($cvPath) }}" class="inline-flex items-center gap-2 px-5 py-3 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 shrink-0" download>
                        <i data-lucide="download" class="w-4 h-4"></i> CV İndir
                    </a>
                @endif
            </div>

            {{-- Sections --}}
            <div class="flex flex-col gap-14">

                {{-- Özet --}}
                <div class="grid grid-cols-1 lg:grid-cols-[160px_1fr] gap-4 lg:gap-8 items-start">
                    <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Özet</div>
                    <p class="m-0 text-base leading-[1.7] opacity-90 text-neutral-950 dark:text-neutral-50">{{ $general->bio ?? '' }}</p>
                </div>

                {{-- Tecrübe --}}
                @if($experiences->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-[160px_1fr] gap-4 lg:gap-8 items-start">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Tecrübe</div>
                        <div class="flex flex-col gap-8">
                            @foreach($experiences as $experience)
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline gap-1 sm:gap-4 mb-1">
                                        <h3 class="m-0 text-[17px] font-semibold text-neutral-950 dark:text-neutral-50">{{ $experience->title }}</h3>
                                        <span class="text-xs text-neutral-500 tabular-nums whitespace-nowrap">{{ $experience->start_date->format('Y') }} — {{ $experience->end_date ? $experience->end_date->format('Y') : 'bugün' }}</span>
                                    </div>
                                    <div class="text-sm text-neutral-600 dark:text-neutral-400 mb-2">{{ $experience->company }}</div>
                                    @if($experience->description)
                                        <p class="m-0 text-sm opacity-85 leading-relaxed text-neutral-950 dark:text-neutral-50">{{ $experience->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Yetkinlikler --}}
                @if($skills->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-[160px_1fr] gap-4 lg:gap-8 items-start">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Yetkinlikler</div>
                        <div class="flex flex-col gap-3.5">
                            @foreach($skills as $skill)
                                <div class="grid grid-cols-[100px_1fr] gap-4 items-baseline">
                                    <span class="text-[13px] text-neutral-600 dark:text-neutral-400">{{ $skill->name }}</span>
                                    <span class="text-sm font-medium text-neutral-950 dark:text-neutral-50">{{ collect($skill->items)->pluck('name')->join(' · ') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Eğitim --}}
                @if($educations->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-[160px_1fr] gap-4 lg:gap-8 items-start">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Eğitim</div>
                        <div class="flex flex-col gap-6">
                            @foreach($educations as $education)
                                <div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-baseline gap-1 sm:gap-4 mb-1">
                                        <h3 class="m-0 text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $education->field }}, {{ $education->degree->getLabel() }}</h3>
                                        <span class="text-xs text-neutral-500 tabular-nums whitespace-nowrap">{{ $education->start_date->format('Y') }} — {{ $education->end_date ? $education->end_date->format('Y') : 'devam' }}</span>
                                    </div>
                                    <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ $education->school }}{{ $education->gpa ? ' · '.$education->gpa : '' }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Diller --}}
                @if($languages->isNotEmpty())
                    <div class="grid grid-cols-1 lg:grid-cols-[160px_1fr] gap-4 lg:gap-8 items-start">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Diller</div>
                        <div class="text-sm leading-8 text-neutral-950 dark:text-neutral-50">
                            @foreach($languages as $language)
                                <div>{{ $language->name }} — <span class="text-neutral-600 dark:text-neutral-400">{{ $language->level->getLabel() }}</span></div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
