@props(['rating' => null, 'max' => 5])

@if($rating)
    <div {{ $attributes->class('flex items-center gap-0.5') }} role="img" aria-label="{{ $max }} üzerinden {{ $rating }}">
        @foreach(range(1, $max) as $star)
            <svg data-star="{{ $star <= $rating ? 'filled' : 'empty' }}" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor" @class(['w-4 h-4', 'text-amber-400' => $star <= $rating, 'text-neutral-200 dark:text-neutral-700' => $star > $rating])>
                <path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1 5.8L10 14.8l-5.2 2.8 1-5.8L1.5 7.7l5.9-.8L10 1.5z" />
            </svg>
        @endforeach
    </div>
@endif
