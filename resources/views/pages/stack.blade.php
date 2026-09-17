@extends('layouts.app')

@section('title', 'Stack')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-xs text-neutral-500 tracking-[1.4px] uppercase mb-3">Stack</div>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Kullandığım teknolojiler.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">Modaya kapılmadan, doğrulanmış araçlarla çalışıyorum. Her teknolojinin yanında o araçla geçirdiğim zamanın yansıması var.</p>

            <div class="mt-16 flex flex-col gap-16">
                @foreach($skills as $skill)
                    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 lg:gap-16 items-start">
                        <div class="lg:sticky lg:top-24">
                            <h2 class="m-0 text-[28px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $skill->name }}</h2>
                            @if($skill->description)
                                <p class="m-0 mt-2 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $skill->description }}</p>
                            @endif
                        </div>
                        <div>
                            @foreach($skill->items as $item)
                                @php
                                    $since = isset($item['since']) && $item['since'] !== '' ? (int) $item['since'] : null;
                                    $years = $since !== null ? max(0, now()->year - $since) : null;
                                @endphp
                                <div class="grid grid-cols-[1fr_auto] sm:grid-cols-[160px_1fr_auto] gap-3 sm:gap-6 items-center py-5 border-b border-neutral-200 dark:border-neutral-800 first:border-t">
                                    <span class="text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $item['name'] }}</span>
                                    <span class="text-sm text-neutral-600 dark:text-neutral-400 hidden sm:block">{{ $item['description'] ?? '' }}</span>
                                    @if($years !== null)
                                        <span class="font-mono text-xs text-neutral-500 tabular-nums whitespace-nowrap" title="{{ $since }}">{{ $years === 0 ? 'Yeni' : $years.' yıl' }}</span>
                                    @else
                                        <span></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
