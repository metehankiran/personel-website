@props(['size' => 'md'])

{{-- Title of a section inside a page. "lg" matches the group titles on the stack page. --}}
<h2 {{ $attributes->class([
    'm-0 font-semibold tracking-tight text-neutral-950 dark:text-neutral-50',
    'text-[22px] leading-tight' => $size === 'md',
    'text-[28px]' => $size === 'lg',
]) }}>{{ $slot }}</h2>
