<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ \App\Support\Seo::route('blog') }}</link>
        <description>{{ $description }}</description>
        <language>tr-TR</language>
        <atom:link href="{{ \App\Support\Seo::route('feed') }}" rel="self" type="application/rss+xml" />
@if($posts->isNotEmpty())
        <lastBuildDate>{{ $posts->max('updated_at')->toRssString() }}</lastBuildDate>
@endif
@foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ \App\Support\Seo::route('blog.show', $post) }}</link>
            <guid isPermaLink="true">{{ \App\Support\Seo::route('blog.show', $post) }}</guid>
            <pubDate>{{ $post->published_at->toRssString() }}</pubDate>
@if($post->category)
            <category>{{ $post->category->name }}</category>
@endif
            <description>{{ \App\Support\Seo::description($post->excerpt, $post->body) }}</description>
        </item>
@endforeach
    </channel>
</rss>
