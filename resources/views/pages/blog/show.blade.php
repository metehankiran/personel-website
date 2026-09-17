@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->excerpt ?? '')
@section('og_type', 'article')
@section('og_image', \App\Support\Images::og($post->cover_image))

@section('content')
    <article>
        {{-- Header --}}
        <header class="pt-8 pb-12 border-b border-neutral-200 dark:border-neutral-800">
            <div class="max-w-[920px] mx-auto px-6 lg:px-12">
                <div class="flex items-center gap-3 mb-8">
                    <a href="{{ route('blog') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors inline-flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Blog
                    </a>
                    <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $post->category->name }}</span>
                </div>

                <h1 class="m-0 text-[36px] sm:text-[44px] lg:text-[56px] leading-[1.05] font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50">{{ $post->title }}</h1>

                @if($post->excerpt)
                    <p class="mt-6 text-xl text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-[720px] m-0 mt-6">{{ $post->excerpt }}</p>
                @endif

                {{-- Byline --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-10 pt-6 border-t border-neutral-200 dark:border-neutral-800">
                    <div class="flex items-center gap-3.5">
                        <span class="w-10 h-10 rounded-full bg-neutral-950 dark:bg-neutral-50 relative inline-block shrink-0">
                            <span class="absolute inset-[7px] bg-white dark:bg-neutral-950 rounded-[13px]"></span>
                        </span>
                        <div>
                            <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-400">{{ $post->published_at->translatedFormat('d F Y') }} · {{ $post->reading_time }} dk okuma</div>
                        </div>
                    </div>
                    <div class="flex gap-1.5">
                        <button class="w-9 h-9 rounded-full bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 cursor-pointer text-[13px] text-neutral-950 dark:text-neutral-50 font-sans transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800 inline-flex items-center justify-center" type="button" aria-label="Twitter'da paylaş">𝕏</button>
                        <button class="w-9 h-9 rounded-full bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 cursor-pointer text-[13px] text-neutral-950 dark:text-neutral-50 font-sans transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800 inline-flex items-center justify-center" type="button" aria-label="LinkedIn'de paylaş">in</button>
                        <button class="w-9 h-9 rounded-full bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 cursor-pointer text-[13px] text-neutral-950 dark:text-neutral-50 font-sans transition-colors hover:bg-neutral-100 dark:hover:bg-neutral-800 inline-flex items-center justify-center" type="button" aria-label="Linki kopyala">
                            <i data-lucide="link" class="w-3.5 h-3.5"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- Cover Image --}}
        @if($post->cover_image)
            <div class="max-w-[920px] mx-auto px-6 lg:px-12 mb-12 mt-12">
                <div class="aspect-video rounded-xl overflow-hidden">
                    <x-image :src="$post->cover_image" fallback="cover" :alt="$post->title" class="w-full h-full object-cover" />
                </div>
            </div>
        @endif

        {{-- Body --}}
        <div class="max-w-[920px] mx-auto px-6 lg:px-12 mt-14">
            <div class="text-[17px] leading-[1.75] max-w-[680px] text-neutral-950 dark:text-neutral-50
                [&>p]:mb-6 [&>p]:opacity-[0.92]
                [&>h2]:mt-14 [&>h2]:mb-4 [&>h2]:text-[26px] [&>h2]:font-semibold [&>h2]:tracking-tight [&>h2]:scroll-mt-24
                [&>ul]:pl-5 [&>ul]:list-disc [&>li]:mb-2 [&>li]:opacity-[0.92]
                [&>ol]:pl-5 [&>ol]:list-decimal
                [&>code]:font-mono [&>code]:text-[13px] [&>code]:bg-neutral-100 dark:[&>code]:bg-neutral-900 [&>code]:px-1.5 [&>code]:py-0.5 [&>code]:rounded
                [&>pre]:bg-neutral-100 dark:[&>pre]:bg-neutral-900 [&>pre]:border [&>pre]:border-neutral-200 dark:[&>pre]:border-neutral-800 [&>pre]:px-5 [&>pre]:py-[18px] [&>pre]:rounded-xl [&>pre]:overflow-x-auto [&>pre]:my-7
                [&>pre>code]:bg-transparent [&>pre>code]:p-0 [&>pre>code]:text-[13px] [&>pre>code]:leading-relaxed
                [&>blockquote]:my-7 [&>blockquote]:px-6 [&>blockquote]:py-4 [&>blockquote]:border-l-[3px] [&>blockquote]:border-neutral-950 dark:[&>blockquote]:border-neutral-50 [&>blockquote]:text-[19px] [&>blockquote]:leading-relaxed [&>blockquote]:font-serif [&>blockquote]:italic [&>blockquote]:opacity-90
                [&>a]:underline [&>a]:underline-offset-2">
                {!! $post->body !!}
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-16 pt-12 border-t border-neutral-200 dark:border-neutral-800">
            <div class="max-w-[920px] mx-auto px-6 lg:px-12">
                @if($post->tags->isNotEmpty())
                    <div class="flex items-center gap-4 mb-12">
                        <div class="text-[11px] text-neutral-500 tracking-[1.2px] uppercase">Etiketler</div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($post->tags as $tag)
                                <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Newsletter CTA --}}
                <livewire:newsletter-form />
            </div>
        </footer>
    </article>

    {{-- Related Posts --}}
    @if($relatedPosts->isNotEmpty())
        <section class="py-10 lg:py-[60px]">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex justify-between items-baseline mb-7">
                    <h2 class="m-0 text-2xl font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Sıradakiler</h2>
                    <a href="{{ route('blog') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">Tüm yazılar →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($relatedPosts as $related)
                        <a href="{{ route('blog.show', $related) }}" class="block p-6 rounded-xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 transition-colors hover:border-neutral-400 dark:hover:border-neutral-600">
                            <div class="aspect-[16/10] rounded-lg mb-4 overflow-hidden border border-neutral-200 dark:border-neutral-800">
                                <x-image :src="$related->cover_image" fallback="cover" :alt="$related->title" class="w-full h-full object-cover" />
                            </div>
                            <h3 class="m-0 text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $related->title }}</h3>
                            <div class="text-[13px] text-neutral-600 dark:text-neutral-400 mt-1.5">{{ $related->reading_time }} dk · {{ $related->category->name }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
