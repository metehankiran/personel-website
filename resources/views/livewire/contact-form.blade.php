@php
    $inputClasses = 'w-full px-3.5 py-2.5 text-sm font-sans bg-white dark:bg-neutral-950 text-neutral-950 dark:text-neutral-50 border border-neutral-200 dark:border-neutral-800 rounded-lg outline-none transition-colors focus:border-neutral-950 dark:focus:border-neutral-50 placeholder:text-neutral-400 dark:placeholder:text-neutral-600';
    $errorInput = 'border-red-400 dark:border-red-500';
@endphp

<div class="p-6 lg:p-10 border border-neutral-200 dark:border-neutral-800 rounded-2xl bg-neutral-50 dark:bg-neutral-900 sticky top-24">
    <div>
        @if($sent)
            <div class="flex flex-col items-center text-center py-8 animate-fade-up motion-reduce:animate-none" wire:key="contact-success">
                <span class="relative inline-flex w-16 h-16 items-center justify-center rounded-full bg-green-500/10 text-green-600 dark:text-green-400">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 6 9 17l-5-5" class="animate-draw-check motion-reduce:animate-none" pathLength="1" />
                    </svg>
                </span>
                <h3 class="mt-5 m-0 text-[22px] font-semibold text-neutral-950 dark:text-neutral-50">Mesajın ulaştı</h3>
                <p class="mt-2 mb-0 text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed max-w-[320px]">Teşekkürler. Genelde 24 saat içinde dönüş yapıyorum.</p>
                <button type="button" wire:click="startOver" class="mt-6 inline-flex items-center gap-2 text-sm font-medium text-neutral-950 dark:text-neutral-50 underline underline-offset-4 decoration-neutral-300 dark:decoration-neutral-700 hover:decoration-current transition-colors bg-transparent border-none cursor-pointer font-sans">
                    Yeni mesaj yaz
                </button>
            </div>
        @else
            <form wire:submit="send" class="flex flex-col gap-5" wire:key="contact-form" novalidate>
                <h3 class="text-[22px] font-semibold text-neutral-950 dark:text-neutral-50 m-0">Hızlıca yaz</h3>

                <div>
                    @error('form')
                        <div class="flex items-start gap-2 text-sm text-red-600 dark:text-red-400 rounded-lg border border-red-200 dark:border-red-900/60 bg-red-50 dark:bg-red-950/40 px-3.5 py-2.5" role="alert">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Honeypot: keep out of the tab order and off-screen; real users never see it --}}
                <div class="absolute -left-[9999px] top-auto w-px h-px overflow-hidden" aria-hidden="true">
                    <label for="contact-website">Web sitesi</label>
                    <input id="contact-website" type="text" name="website" wire:model="website" tabindex="-1" autocomplete="off" />
                </div>

                <div>
                    <label for="contact-name" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Adın</label>
                    <input id="contact-name" type="text" name="name" wire:model="name" autocomplete="name"
                        class="{{ $inputClasses }} @error('name') {{ $errorInput }} @enderror"
                        placeholder="Adınız" />
                    <div>@error('name') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                </div>

                <div>
                    <label for="contact-email" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Email</label>
                    <input id="contact-email" type="email" name="email" wire:model="email" autocomplete="email"
                        class="{{ $inputClasses }} @error('email') {{ $errorInput }} @enderror"
                        placeholder="siz@example.com" />
                    <div>@error('email') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                </div>

                <div>
                    <label for="contact-phone" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Telefon <span class="text-neutral-400">(isteğe bağlı)</span></label>
                    <input id="contact-phone" type="tel" name="phone" wire:model="phone" autocomplete="tel"
                        class="{{ $inputClasses }} @error('phone') {{ $errorInput }} @enderror"
                        placeholder="+90 5XX XXX XX XX" />
                    <div>@error('phone') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                </div>

                <div>
                    <label for="contact-subject" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Konu</label>
                    <div class="relative">
                        <select id="contact-subject" name="subject" wire:model="subject"
                            class="{{ $inputClasses }} appearance-none pr-9 @error('subject') {{ $errorInput }} @enderror">
                            <option value="">Seçiniz</option>
                            @foreach($subjects as $option)
                                <option value="{{ $option->value }}">{{ $option->getLabel() }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                    </div>
                    <div>@error('subject') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                </div>

                <div>
                    <label for="contact-message" class="block text-xs text-neutral-500 mb-1.5 tracking-wide">Mesaj</label>
                    <textarea id="contact-message" name="message" wire:model="message" rows="5"
                        class="{{ $inputClasses }} resize-y @error('message') {{ $errorInput }} @enderror"
                        placeholder="Projeyi anlatabilir misin..."></textarea>
                    <div>@error('message') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                </div>

                @if($kvkkPageSlug)
                    <div>
                        <label class="flex items-start gap-2 text-[13px] text-neutral-600 dark:text-neutral-400 cursor-pointer">
                            <input type="checkbox" name="kvkk_consent" wire:model="kvkk_consent" class="mt-0.5 accent-neutral-950 dark:accent-neutral-50" />
                            <span><a href="{{ route('pages.show', $kvkkPageSlug) }}" target="_blank" class="underline underline-offset-2 hover:text-neutral-950 dark:hover:text-neutral-50 transition-colors">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum.</span>
                        </label>
                        <div>@error('kvkk_consent') <p class="text-xs text-red-500 mt-1 m-0">{{ $message }}</p> @enderror</div>
                    </div>
                @endif

                <button type="submit" wire:loading.attr="disabled" wire:target="send"
                    class="group relative inline-flex items-center justify-center gap-2 px-5 py-3.5 text-sm font-medium rounded-xl bg-neutral-950 dark:bg-neutral-50 text-white dark:text-neutral-950 transition-all hover:opacity-85 cursor-pointer border-none font-sans disabled:cursor-wait disabled:opacity-90 overflow-hidden">
                    <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 dark:via-neutral-950/10 to-transparent" wire:loading.class="animate-shimmer" wire:target="send" aria-hidden="true"></span>

                    <span class="relative inline-flex items-center gap-2" wire:loading.remove wire:target="send">
                        Gönder
                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </span>

                    <span class="relative inline-flex items-center gap-2.5" wire:loading.inline-flex wire:target="send" aria-live="polite">
                        <svg class="w-4 h-4 animate-spin motion-reduce:animate-none" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2.5" class="opacity-25"/>
                            <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                        Gönderiliyor
                        <span class="inline-flex gap-0.5" aria-hidden="true">
                            <span class="w-1 h-1 rounded-full bg-current animate-dot motion-reduce:animate-none"></span>
                            <span class="w-1 h-1 rounded-full bg-current animate-dot [animation-delay:150ms] motion-reduce:animate-none"></span>
                            <span class="w-1 h-1 rounded-full bg-current animate-dot [animation-delay:300ms] motion-reduce:animate-none"></span>
                        </span>
                    </span>
                </button>
            </form>
        @endif
    </div>
</div>
