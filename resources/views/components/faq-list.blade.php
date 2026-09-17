@props(['faqs'])

{{-- Native <details> keeps every answer in the HTML for crawlers and needs no JavaScript. --}}
<div {{ $attributes->class(['max-w-[820px] border-t border-neutral-200 dark:border-neutral-800']) }}>
    @foreach($faqs as $faq)
        <details class="group border-b border-neutral-200 dark:border-neutral-800" @if($loop->first) open @endif>
            <summary class="flex items-center justify-between gap-6 py-5 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <h3 class="m-0 text-[17px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">{{ $faq->question }}</h3>
                <svg class="w-4 h-4 shrink-0 text-neutral-400 transition-transform duration-200 group-open:rotate-180 motion-reduce:transition-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
            </summary>
            <p class="m-0 pb-6 pr-10 text-[15px] leading-[1.7] text-neutral-600 dark:text-neutral-400">{!! nl2br(e($faq->answer)) !!}</p>
        </details>
    @endforeach
</div>
