<div class="relative overflow-hidden rounded-2xl border border-neutral-200 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
    {{-- Decorative layer --}}
    <div class="pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-neutral-200/70 dark:bg-neutral-800/60 blur-3xl"></div>
        <span class="hidden sm:block absolute -bottom-16 right-4 text-[220px] leading-none font-serif italic text-neutral-200/80 dark:text-neutral-800/70 select-none">@</span>
    </div>

    <div class="relative px-6 py-8 sm:px-10 sm:py-10">
        @if($subscribed)
            <div class="flex items-start gap-4 animate-fade-up motion-reduce:animate-none" wire:key="newsletter-success" role="status">
                <span class="inline-flex w-11 h-11 shrink-0 items-center justify-center rounded-full bg-green-500/10 text-green-600 dark:text-green-400">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" class="animate-draw-check motion-reduce:animate-none" pathLength="1" />
                    </svg>
                </span>
                <div>
                    <h3 class="m-0 text-[22px] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">Aboneliğin tamam</h3>
                    <p class="mt-1.5 mb-0 text-sm leading-relaxed text-neutral-600 dark:text-neutral-400 max-w-[420px]">Teşekkürler. Yeni bir yazı yayınladığımda ilk senin haberin olacak.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-7 md:grid-cols-[1fr_minmax(0,360px)] md:items-end md:gap-10" wire:key="newsletter-form">
                <div>
                    <div class="flex items-center gap-2 text-[11px] text-neutral-500 tracking-[1.2px] uppercase">
                        <span class="relative flex w-1.5 h-1.5">
                            <span class="absolute inline-flex w-full h-full rounded-full bg-green-500 opacity-60 animate-ping motion-reduce:animate-none"></span>
                            <span class="relative inline-flex w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        </span>
                        Bülten
                    </div>
                    <h3 class="mt-3 mb-0 text-[26px] sm:text-[30px] leading-[1.15] font-semibold tracking-tight text-neutral-950 dark:text-neutral-50">
                        Bu yazıdan <span class="font-serif italic font-medium">hoşlandın mı?</span>
                    </h3>
                    <p class="mt-3 mb-0 text-[15px] leading-relaxed text-neutral-600 dark:text-neutral-400 max-w-[380px]">Ayda 1-2 yazı. Yenisi çıkınca email'ine düşüversin.</p>
                </div>

                <form wire:submit="subscribe" class="min-w-0" novalidate>
                    {{-- Honeypot: keep out of the tab order and off-screen; real users never see it --}}
                    <div class="absolute -left-[9999px] top-auto w-px h-px overflow-hidden" aria-hidden="true">
                        <label for="newsletter-website">Web sitesi</label>
                        <input id="newsletter-website" type="text" name="website" wire:model="website" tabindex="-1" autocomplete="off" />
                    </div>

                    <label for="newsletter-email" class="sr-only">E-posta adresin</label>
                    <div class="flex items-center gap-1.5 p-1.5 rounded-xl bg-white dark:bg-neutral-950 border transition-colors focus-within:border-neutral-950 dark:focus-within:border-neutral-50 @if($errors->has('email') || $errors->has('form')) border-red-400 dark:border-red-500 @else border-neutral-200 dark:border-neutral-800 @endif">
                        <input id="newsletter-email" type="email" name="email" wire:model="email" autocomplete="email" inputmode="email"
                            class="flex-1 min-w-0 px-2.5 py-2 text-sm font-sans bg-transparent text-neutral-950 dark:text-neutral-50 border-none outline-none placeholder:text-neutral-400 dark:placeholder:text-neutral-600"
                            placeholder="email@adres"
                            @error('email') aria-invalid="true" aria-describedby="newsletter-email-error" @enderror />

                        <button type="submit" wire:loading.attr="disabled" wire:target="subscribe"
                            class="group relative inline-flex items-center justify-center gap-1.5 shrink-0 px-4 py-2 text-sm font-medium rounded-lg bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-opacity hover:opacity-85 cursor-pointer border-none font-sans disabled:cursor-wait disabled:opacity-90 overflow-hidden">
                            <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 dark:via-neutral-950/10 to-transparent" wire:loading.class="animate-shimmer" wire:target="subscribe" aria-hidden="true"></span>

                            <span class="relative inline-flex items-center gap-1.5" wire:loading.remove wire:target="subscribe">
                                Abone ol
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </span>

                            <span class="relative inline-flex items-center gap-2" wire:loading.inline-flex wire:target="subscribe" aria-live="polite">
                                <svg class="w-3.5 h-3.5 animate-spin motion-reduce:animate-none" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" class="opacity-25"/>
                                    <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                </svg>
                                Ekleniyor
                            </span>
                        </button>
                    </div>

                    <div class="mt-2.5 min-h-[18px] text-xs">
                        @if($errors->has('email'))
                            <p id="newsletter-email-error" class="m-0 text-red-500" role="alert">{{ $errors->first('email') }}</p>
                        @elseif($errors->has('form'))
                            <p class="m-0 text-red-500" role="alert">{{ $errors->first('form') }}</p>
                        @else
                            <p class="m-0 text-neutral-500">Spam yok. Tek tıkla istediğin an çıkarsın.</p>
                        @endif
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
