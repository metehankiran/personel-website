@php
    $portrait = app(\App\Settings\AboutSettings::class)->portrait_path;
    $bio = \Illuminate\Support\Str::of(strip_tags((string) $general->bio))->squish()->limit(240);
@endphp

<aside data-author-box {{ $attributes->class('flex flex-col sm:flex-row gap-5 p-7 rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900') }}>
    <x-image :src="$portrait" fallback="avatar" :alt="$general->author_name" class="w-16 h-16 rounded-full object-cover shrink-0" width="64" height="64" />
    <div class="min-w-0">
        <x-eyebrow size="sm">Yazar hakkında</x-eyebrow>
        <div class="mt-1.5 text-base font-semibold text-neutral-950 dark:text-neutral-50">{{ $general->author_name }}</div>
        @if(filled($general->author_title))
            <div class="text-sm text-neutral-600 dark:text-neutral-400 mt-0.5">{{ $general->author_title }}</div>
        @endif
        @if($bio->isNotEmpty())
            <p class="m-0 mt-3 text-[15px] leading-relaxed text-neutral-700 dark:text-neutral-300">{{ $bio }}</p>
        @endif
        <a href="{{ route('about') }}" rel="author" class="inline-block mt-4 text-sm font-medium text-neutral-950 dark:text-neutral-50 underline underline-offset-4 decoration-neutral-300 dark:decoration-neutral-700 hover:decoration-neutral-950 dark:hover:decoration-neutral-50">Hakkımda daha fazla →</a>
    </div>
</aside>
