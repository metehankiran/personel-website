{{-- Opened by dispatching `open-contact-modal` with a `subject` (service id) in the event detail. --}}
<div id="contact-modal"
    x-data="{ open: false }"
    x-cloak
    x-show="open"
    x-on:open-contact-modal.window="open = true; Livewire.dispatch('contact-subject-selected', { subject: String($event.detail.subject ?? '') })"
    x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:p-6"
    role="dialog" aria-modal="true" aria-label="İletişim formu">

    <div x-show="open" x-transition.opacity.duration.200ms x-on:click="open = false"
        class="absolute inset-0 bg-neutral-950/60 backdrop-blur-sm" aria-hidden="true"></div>

    <div x-show="open" x-trap.noscroll.inert="open"
        x-transition:enter="transition ease-out duration-200 motion-reduce:transition-none"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150 motion-reduce:transition-none"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="relative w-full sm:max-w-[540px] max-h-[92dvh] overflow-y-auto overscroll-contain rounded-t-2xl sm:rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900 shadow-[0_24px_64px_rgba(0,0,0,0.25)]">

        <button type="button" x-on:click="open = false" aria-label="Kapat"
            class="absolute top-4 right-4 z-10 inline-flex w-9 h-9 items-center justify-center rounded-full text-neutral-500 hover:text-neutral-950 dark:hover:text-neutral-50 hover:bg-neutral-200/70 dark:hover:bg-neutral-800 transition-colors bg-transparent border-none cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>

        <livewire:contact-form :in-modal="true" />
    </div>
</div>
