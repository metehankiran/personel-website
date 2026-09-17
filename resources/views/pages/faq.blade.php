@extends('layouts.app')

@section('title', 'Sıkça Sorulan Sorular')
@section('meta_description', $faqs->isNotEmpty() ? 'Sıkça sorulan sorular: '.$faqs->take(4)->pluck('question')->join(' ') : 'Birlikte çalışmaya dair sıkça sorulan sorular ve yanıtları: fiyatlandırma, süre, süreç ve teslim sonrası destek.')

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::faqPage($faqs), \App\Support\Schema::breadcrumbs(['Sıkça Sorulan Sorular' => \App\Support\Seo::route('faq')])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-eyebrow class="mb-3">SSS</x-eyebrow>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Sıkça sorulan sorular.</h1>
            <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[580px]">Birlikte çalışmaya başlamadan önce en çok merak edilenler. Aradığın yanıt burada yoksa doğrudan yazabilirsin.</p>

            @if($faqs->isEmpty())
                <x-empty-state class="mt-12" icon="circle-help" title="Henüz bir soru eklenmedi" description="Sık gelen sorular ve yanıtları burada yer alacak." action-label="Sorunu doğrudan sor" :action-url="route('contact')" />
            @else
                <x-faq-list :faqs="$faqs" class="mt-14" />

                <div class="mt-14 flex flex-wrap items-center gap-x-6 gap-y-3">
                    <span class="text-[15px] text-neutral-600 dark:text-neutral-400">Yanıtını bulamadın mı?</span>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85">Bana yaz <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                </div>
            @endif
        </div>
    </section>
@endsection
