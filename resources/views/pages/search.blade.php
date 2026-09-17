@extends('layouts.app')

@section('title', $query !== '' ? '“'.$query.'” için arama' : 'Arama')
@section('meta_description', 'Sitedeki sayfalar, projeler ve blog yazıları içinde arama yapın.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <x-eyebrow class="mb-3">Arama</x-eyebrow>
            <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">Sitede ara.</h1>

            <form action="{{ route('search') }}" method="GET" role="search" class="mt-10 flex gap-2 max-w-[640px]">
                <label for="search-query" class="sr-only">Arama terimi</label>
                <input id="search-query" type="search" name="q" value="{{ $query }}" maxlength="100" placeholder="Sayfa, proje veya yazı ara" autocomplete="off" class="flex-1 min-w-0 px-4 py-3 text-[15px] rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-neutral-950 dark:text-neutral-50 placeholder:text-neutral-400 focus:outline-none focus:border-neutral-950 dark:focus:border-neutral-50">
                <button type="submit" class="px-5 py-3 text-sm font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 cursor-pointer">Ara</button>
            </form>

            <div class="mt-14 max-w-[820px]">
                @if($query === '')
                    <x-empty-state icon="search" title="Aramak için bir şey yaz" description="Sayfalar, projeler ve blog yazıları içinde arar." />
                @elseif($results->isEmpty())
                    <x-empty-state icon="search-x" :title="'“'.$query.'” için sonuç bulunamadı'" description="Başka bir kelime dene ya da yazılara göz at." action-label="Blog'a git" :action-url="route('blog')" />
                @else
                    @foreach($results as $type => $items)
                        <x-eyebrow size="sm" @class(['mb-3', 'mt-12' => ! $loop->first])>{{ $type }}</x-eyebrow>
                        <ul class="m-0 p-0 list-none border-t border-neutral-200 dark:border-neutral-800">
                            @foreach($items as $item)
                                <li class="border-b border-neutral-200 dark:border-neutral-800">
                                    <a href="{{ $item['url'] }}" class="group flex items-start gap-4 py-5">
                                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 mt-1 shrink-0 text-neutral-400"></i>
                                        <span class="min-w-0">
                                            <span class="block text-[17px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50 group-hover:underline underline-offset-4">{{ $item['title'] }}</span>
                                            @if(filled($item['desc']))
                                                <span class="block mt-1 text-[15px] text-neutral-600 dark:text-neutral-400">{{ $item['desc'] }}</span>
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
