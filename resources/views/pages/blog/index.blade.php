@extends('layouts.app')

@section('title', ($activeCategory ? $activeCategory->name . ' — ' : ($activeTag ? $activeTag->name . ' — ' : '')) . 'Blog')
@section('meta_description', $activeCategory ? $activeCategory->name.' kategorisindeki yazılar: çalışırken karşılaşılan problemler, denenen çözümler ve öğrenilenler.' : ($activeTag ? $activeTag->name.' etiketli yazılar: çalışırken karşılaşılan problemler, denenen çözümler ve öğrenilenler.' : 'Yazılım geliştirme üzerine yazılar'.($categories->isNotEmpty() ? ': '.$categories->take(5)->pluck('name')->join(', ') : '').'. Çalışırken karşılaşılan problemler ve öğrenilenler.'))

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::breadcrumbs(['Blog' => \App\Support\Seo::route('blog')])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10 lg:gap-16 items-start">

                {{-- Posts --}}
                <div>
                    <x-eyebrow class="mb-3">Blog</x-eyebrow>
                    <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $activeCategory ? $activeCategory->name : ($activeTag ? '#'.$activeTag->name : 'Yazılar.') }}</h1>
                    <p class="text-[17px] text-neutral-600 dark:text-neutral-400 leading-relaxed mt-5 max-w-[480px]">Çalışırken karşılaştığım problemleri, deneyimleri ve denedikçe öğrendiklerimi yazıyorum.</p>

                    <div class="mt-12">
                        @forelse($posts as $post)
                            <a href="{{ route('blog.show', $post) }}" class="block py-8 border-b border-neutral-200 dark:border-neutral-800 first:border-t transition-colors hover:opacity-90">
                                <div class="flex gap-4 items-center mb-3">
                                    <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium whitespace-nowrap text-neutral-950 dark:text-neutral-50">{{ $post->category->name }}</span>
                                    <time datetime="{{ $post->published_at->toIso8601String() }}" class="text-xs text-neutral-500 tabular-nums">{{ $post->published_at->format('Y-m-d') }}</time>
                                    <span class="text-xs text-neutral-500">· {{ $post->reading_time }} dk okuma</span>
                                </div>
                                <h2 class="m-0 mb-2 text-[26px] font-semibold tracking-tight leading-tight text-neutral-950 dark:text-neutral-50">{{ $post->title }}</h2>
                                @if($post->excerpt)
                                    <p class="m-0 text-[15px] text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-[640px]">{{ $post->excerpt }}</p>
                                @endif
                            </a>
                        @empty
                            @if($activeCategory || $activeTag)
                                <x-empty-state icon="search-x" title="Bu filtrede yazı bulunamadı" action-label="Tüm yazılara dön" :action-url="route('blog')" />
                            @else
                                <x-empty-state icon="notebook-pen" title="Henüz bir yazı yayınlanmadı" description="İlk yazı yayınlandığında burada görünecek." />
                            @endif
                        @endforelse
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="flex flex-col gap-8 lg:sticky lg:top-24">
                    @if($categories->isNotEmpty())
                        <div>
                            <x-eyebrow size="sm" class="mb-4">Kategoriler</x-eyebrow>
                            <div class="flex flex-wrap gap-1.5">
                                <a href="{{ route('blog') }}" @class([
                                    'text-xs px-3 py-1.5 rounded-full font-medium border transition-colors',
                                    'bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 border-neutral-950 dark:border-neutral-50' => !$activeCategory && !$activeTag,
                                    'bg-transparent text-neutral-600 dark:text-neutral-400 border-neutral-200 dark:border-neutral-800 hover:border-neutral-400 dark:hover:border-neutral-600' => $activeCategory || $activeTag,
                                ])>Hepsi</a>
                                @foreach($categories as $category)
                                    <a href="{{ route('blog.category', $category) }}" @class([
                                        'text-xs px-3 py-1.5 rounded-full font-medium border transition-colors',
                                        'bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 border-neutral-950 dark:border-neutral-50' => $activeCategory?->id === $category->id,
                                        'bg-transparent text-neutral-600 dark:text-neutral-400 border-neutral-200 dark:border-neutral-800 hover:border-neutral-400 dark:hover:border-neutral-600' => $activeCategory?->id !== $category->id,
                                    ])>{{ $category->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($tags->isNotEmpty())
                        <div>
                            <x-eyebrow size="sm" class="mb-4">Etiketler</x-eyebrow>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($tags as $tag)
                                    <a href="{{ route('blog.tag', $tag) }}" @class([
                                        'text-[11px] px-2 py-0.5 rounded-full font-medium transition-colors',
                                        'bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950' => $activeTag?->id === $tag->id,
                                        'bg-neutral-100 dark:bg-neutral-900 text-neutral-950 dark:text-neutral-50' => $activeTag?->id !== $tag->id,
                                    ])>{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Newsletter --}}
                    <div class="p-6 border border-neutral-200 dark:border-neutral-800 rounded-xl bg-neutral-50 dark:bg-neutral-900">
                        <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50 mb-1.5">Yeni yazılar için bülten</div>
                        <p class="m-0 mb-3 text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">Ayda 1-2 yazı, spam yok.</p>
                        @if(session('success'))
                            <p class="text-[13px] text-green-600 dark:text-green-400">{{ session('success') }}</p>
                        @endif
                        <form action="{{ route('newsletter.subscribe') }}" method="POST">
                            @csrf
                            <input class="w-full px-3 py-2 text-[13px] font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 placeholder:text-neutral-400 dark:placeholder:text-neutral-600 mb-2" name="email" type="email" placeholder="email@adres" required />
                            @error('email')
                                <p class="text-xs text-red-500 mb-2">{{ $message }}</p>
                            @enderror
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 text-[13px] font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 cursor-pointer border-none font-sans">Abone ol</button>
                        </form>
                    </div>
                </aside>

            </div>
        </div>
    </section>
@endsection
