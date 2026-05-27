@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp
<footer class="site-footer" role="contentinfo">
    <div class="footer-inner">
        <div>
            <div class="footer-brand"><span class="brand-mark"></span><span>{{ $general->author_name }}</span></div>
            @if($general->footer_text)
                <p class="footer-text">{{ $general->footer_text }}</p>
            @endif
        </div>
        <div>
            <div class="footer-col-title">Site</div>
            <div class="footer-links">
                <a href="{{ route('home') }}">Ana sayfa</a>
                <a href="{{ $link('about') }}">Hakkımda</a>
                <a href="{{ $link('projects') }}">Projeler</a>
                <a href="{{ $link('blog') }}">Blog</a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">İşler</div>
            <div class="footer-links">
                <a href="{{ $link('services') }}">Hizmetler</a>
                <a href="{{ $link('stack') }}">Stack</a>
                <a href="{{ $link('references') }}">Referanslar</a>
                <a href="{{ $link('cv') }}">CV</a>
            </div>
        </div>
        <div>
            <div class="footer-col-title">Bağlan</div>
            <div class="footer-links">
                @if($general->author_email)
                    <a href="mailto:{{ $general->author_email }}">Email</a>
                @endif
                @if($social->github_url)
                    <a href="{{ $social->github_url }}" target="_blank" rel="noopener">GitHub</a>
                @endif
                @if($social->linkedin_url)
                    <a href="{{ $social->linkedin_url }}" target="_blank" rel="noopener">LinkedIn</a>
                @endif
                @if($social->twitter_url)
                    <a href="{{ $social->twitter_url }}" target="_blank" rel="noopener">Twitter</a>
                @endif
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ now()->year }} {{ $general->author_name }}</span>
        <span>{{ $general->author_location ?? '' }}</span>
    </div>
</footer>
