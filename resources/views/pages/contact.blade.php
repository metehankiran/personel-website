@extends('layouts.app')

@section('title', 'İletişim — ' . $general->site_title)

@section('content')
    <section><div class="container">
        <div class="split-2" style="grid-template-columns:1fr 1fr;align-items:start;">
            <div>
                <div class="eyebrow">İletişim</div>
                <h1 class="h1">Birlikte<br/>bir şey yapalım.</h1>
                <p class="lede" style="max-width:480px;">Bir proje fikrin mi var? Sadece selam mı vereceksin? Her ikisi de iyi. Genelde 24 saat içinde dönüyorum.</p>
                <div style="margin-top:48px;">
                    @if($general->author_email)
                        <a href="mailto:{{ $general->author_email }}" class="contact-link"><div><div class="contact-link-key">Email</div><div class="contact-link-val">{{ $general->author_email }}</div></div><span class="contact-link-icon">✉</span></a>
                    @endif
                    @if($general->author_phone)
                        <a href="tel:{{ $general->author_phone }}" class="contact-link"><div><div class="contact-link-key">Telefon</div><div class="contact-link-val">{{ $general->author_phone }}</div></div><span class="contact-link-icon">☎</span></a>
                    @endif
                    @if($social->github_url)
                        <a href="{{ $social->github_url }}" class="contact-link" target="_blank" rel="noopener"><div><div class="contact-link-key">GitHub</div><div class="contact-link-val">{{ str_replace('https://', '', $social->github_url) }}</div></div><span class="contact-link-icon">↗</span></a>
                    @endif
                    @if($social->linkedin_url)
                        <a href="{{ $social->linkedin_url }}" class="contact-link" target="_blank" rel="noopener"><div><div class="contact-link-key">LinkedIn</div><div class="contact-link-val">{{ str_replace('https://', '', $social->linkedin_url) }}</div></div><span class="contact-link-icon">↗</span></a>
                    @endif
                    @if($social->twitter_url)
                        <a href="{{ $social->twitter_url }}" class="contact-link" target="_blank" rel="noopener"><div><div class="contact-link-key">Twitter</div><div class="contact-link-val">{{ str_replace('https://', '', $social->twitter_url) }}</div></div><span class="contact-link-icon">↗</span></a>
                    @endif
                    @if($social->instagram_url)
                        <a href="{{ $social->instagram_url }}" class="contact-link" target="_blank" rel="noopener"><div><div class="contact-link-key">Instagram</div><div class="contact-link-val">{{ str_replace('https://', '', $social->instagram_url) }}</div></div><span class="contact-link-icon">↗</span></a>
                    @endif
                    @if($general->google_maps_url)
                        <a href="{{ $general->google_maps_url }}" class="contact-link" target="_blank" rel="noopener"><div><div class="contact-link-key">Harita</div><div class="contact-link-val">{{ $general->author_location ?? 'Konum' }}</div></div><span class="contact-link-icon">📍</span></a>
                    @endif
                </div>
            </div>
            <form class="form" action="{{ route('contact.send') }}" method="POST">
                @csrf
                <h3>Hızlıca yaz</h3>
                @if(session('success'))
                    <p style="font-size:14px;color:var(--success);margin:0 0 16px;">{{ session('success') }}</p>
                @endif
                <div class="form-row">
                    <label for="contact-name">Adın</label>
                    <input id="contact-name" type="text" name="name" class="input" placeholder="Adınız" value="{{ old('name') }}" required />
                    @error('name') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div class="form-row">
                    <label for="contact-email">Email</label>
                    <input id="contact-email" type="email" name="email" class="input" placeholder="siz@example.com" value="{{ old('email') }}" required />
                    @error('email') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div class="form-row">
                    <label for="contact-phone">Telefon</label>
                    <input id="contact-phone" type="tel" name="phone" class="input" placeholder="+90 5XX XXX XX XX" value="{{ old('phone') }}" />
                    @error('phone') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div class="form-row">
                    <label for="contact-subject">Konu</label>
                    <select id="contact-subject" name="subject" class="input" required>
                        <option value="">Seçiniz</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->value }}" {{ old('subject') === $subject->value ? 'selected' : '' }}>{{ $subject->getLabel() }}</option>
                        @endforeach
                    </select>
                    @error('subject') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div class="form-row">
                    <label for="contact-message">Mesaj</label>
                    <textarea id="contact-message" name="message" rows="5" class="input" placeholder="Projeyi anlatabilir misin..." style="resize:vertical;" required>{{ old('message') }}</textarea>
                    @error('message') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                </div>
                @if($general->kvkk_page_slug)
                    <div class="form-row">
                        <label style="display:flex;align-items:start;gap:8px;font-size:13px;cursor:pointer;">
                            <input type="checkbox" name="kvkk_consent" value="1" required style="margin-top:3px;" />
                            <span><a href="{{ route('pages.show', $general->kvkk_page_slug) }}" target="_blank">KVKK Aydınlatma Metni</a>'ni okudum ve kabul ediyorum.</span>
                        </label>
                        @error('kvkk_consent') <p style="font-size:12px;color:red;margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>
                @endif
                <button type="submit" class="btn btn-primary">Gönder →</button>
            </form>
        </div>
    </div></section>
@endsection
