@php
    $link = fn (string $name): string => Route::has($name) ? route($name) : '#';
@endphp
<footer class="site-footer" role="contentinfo">
    <div class="footer-inner">
        <div>
            <div class="footer-brand"><span class="brand-mark"></span><span>Metehan Kıran</span></div>
            <p class="footer-text">Bağımsız full-stack developer. Laravel, Vue ve .NET Core ile ürünler inşa ediyorum. İstanbul'dan, dünyanın her yerine.</p>
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
                <a href="mailto:merhaba@metehankiran.dev">Email</a>
                <a href="#" rel="noopener">GitHub</a>
                <a href="#" rel="noopener">LinkedIn</a>
                <a href="#" rel="noopener">Twitter</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ now()->year }} Metehan Kıran</span>
        <span>İstanbul, Türkiye</span>
    </div>
</footer>
