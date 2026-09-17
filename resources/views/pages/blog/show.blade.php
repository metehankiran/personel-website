@extends('layouts.app')

@section('robots', $post->isPublished() ? 'index, follow' : 'noindex, follow')
@section('title', $post->title)
@section('meta_description', \App\Support\Seo::description($post->excerpt, $post->body))
@section('og_type', 'article')
@section('og_image', \App\Support\Images::og($post->cover_image))

@push('meta')
    @if($post->published_at)
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
@endpush

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::blogPosting($post), \App\Support\Schema::breadcrumbs(['Blog' => \App\Support\Seo::route('blog'), $post->title => \App\Support\Seo::route('blog.show', $post)])) }}
@endpush

@section('content')
    <article>
        {{-- Header --}}
        <header class="pt-8 pb-12 border-b border-neutral-200 dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <div class="flex items-center gap-3 mb-8">
                    <a href="{{ route('blog') }}" class="text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors inline-flex items-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Blog
                    </a>
                    <span class="inline-block text-[11px] px-2 py-0.5 bg-neutral-100 dark:bg-neutral-900 rounded-full font-medium text-neutral-950 dark:text-neutral-50">{{ $post->category->name }}</span>
                </div>

                <h1 data-speakable="headline" class="m-0 max-w-4xl text-[36px] sm:text-[44px] lg:text-[56px] leading-[1.05] font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50">{{ $post->title }}</h1>

                @if($post->excerpt)
                    <p data-speakable="summary" class="mt-6 text-xl text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-[720px] m-0 mt-6">{{ $post->excerpt }}</p>
                @endif

                {{-- Byline --}}
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mt-10 pt-6 border-t border-neutral-200 dark:border-neutral-800">
                    <div class="flex items-center gap-3.5">
                        <x-brand-mark class="w-10 h-10" />
                        <div>
                            <div class="text-sm font-semibold text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-400">@if($post->published_at)<time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time>@else<span>Taslak</span>@endif · {{ $post->reading_time }} dk okuma</div>
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

        {{-- Cover + body beside a sidebar: the post starts at the shared left edge and fills the shared container. --}}
        <div class="max-w-7xl mx-auto px-6 lg:px-12 mt-12">
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10 lg:gap-16 items-start">
                <div class="min-w-0">
                    @if($post->cover_image)
                        <div class="aspect-video rounded-xl overflow-hidden mb-12">
                            <x-image :src="$post->cover_image" fallback="cover" :alt="$post->title" class="w-full h-full object-cover" />
                        </div>
                    @endif

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

                <aside data-post-aside class="flex flex-col gap-8 lg:sticky lg:top-24">
                    <div>
                        <x-eyebrow size="sm" class="mb-4">Kategori</x-eyebrow>
                        <a href="{{ route('blog.category', $post->category) }}" class="inline-block text-xs px-3 py-1.5 rounded-full font-medium border border-neutral-200 dark:border-neutral-800 text-neutral-600 dark:text-neutral-400 hover:border-neutral-400 dark:hover:border-neutral-600 transition-colors">{{ $post->category->name }}</a>
                    </div>

                    @if($post->tags->isNotEmpty())
                        <div>
                            <x-eyebrow size="sm" class="mb-4">Etiketler</x-eyebrow>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag) }}" class="text-[11px] px-2 py-0.5 rounded-full font-medium bg-neutral-100 dark:bg-neutral-900 text-neutral-950 dark:text-neutral-50 transition-colors hover:bg-neutral-200 dark:hover:bg-neutral-800">{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('blog') }}" class="inline-flex items-center gap-1.5 text-[13px] text-neutral-600 dark:text-neutral-400 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors"><i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Tüm yazılar</a>
                </aside>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-16 pt-12 border-t border-neutral-200 dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-6 lg:px-12">
                <x-author-box class="max-w-[680px] mb-12" />

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
