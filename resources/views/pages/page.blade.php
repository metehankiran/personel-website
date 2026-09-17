@extends('layouts.app')

@section('robots', $page->isPublished() ? 'index, follow' : 'noindex, follow')
@section('title', $page->title)
@section('meta_description', \App\Support\Seo::description($page->body))

@push('schema')
    {{ \App\Support\Schema::script(\App\Support\Schema::breadcrumbs([$page->title => \App\Support\Seo::route('pages.show', $page)])) }}
@endpush

@section('content')
    <section class="py-10 lg:py-[60px] first-of-type:pt-12 lg:first-of-type:pt-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="mb-12">
                <h1 class="text-[40px] sm:text-[52px] lg:text-[64px] leading-none font-medium tracking-tighter text-balance text-neutral-950 dark:text-neutral-50 m-0">{{ $page->title }}</h1>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_280px] gap-10 lg:gap-16 items-start">
                <div class="flex flex-col gap-[18px] text-[17px] leading-[1.7] opacity-90 max-w-[760px] [&>p]:m-0 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:text-2xl [&>h2]:font-semibold [&>h2]:tracking-tight [&>h2]:text-neutral-950 dark:[&>h2]:text-neutral-50 [&>ul]:pl-5 [&>ul]:list-disc [&_a]:underline [&_a]:underline-offset-2 [&_a]:hover:text-neutral-950 dark:[&_a]:hover:text-neutral-50 [&>ol]:pl-5 [&>ol]:list-decimal text-neutral-600 dark:text-neutral-400">
                    {!! $page->body !!}
                </div>

                <x-pages-aside :current="$page->slug" :updated-at="$page->updated_at" />
            </div>
        </div>
    </section>
@endsection
