@extends('layouts.app')

@section('title', 'İletişim — Metehan Kıran')

@section('content')
    <section><div class="container">
        <div class="split-2" style="grid-template-columns:1fr 1fr;align-items:start;">
            <div>
                <div class="eyebrow">İletişim</div>
                <h1 class="h1">Birlikte<br/>bir şey yapalım.</h1>
                <p class="lede" style="max-width:480px;">Bir proje fikrin mi var? Sadece selam mı vereceksin? Her ikisi de iyi. Genelde 24 saat içinde dönüyorum.</p>
                <div style="margin-top:48px;">
                    <a href="mailto:merhaba@metehankiran.dev" class="contact-link"><div><div class="contact-link-key">Email</div><div class="contact-link-val">merhaba@metehankiran.dev</div></div><span class="contact-link-icon">✉</span></a>
                    <a href="#" class="contact-link"><div><div class="contact-link-key">GitHub</div><div class="contact-link-val">github.com/metehankiran</div></div><span class="contact-link-icon">↗</span></a>
                    <a href="#" class="contact-link"><div><div class="contact-link-key">LinkedIn</div><div class="contact-link-val">in/metehankiran</div></div><span class="contact-link-icon">↗</span></a>
                    <a href="#" class="contact-link"><div><div class="contact-link-key">Twitter</div><div class="contact-link-val">@metehankiran</div></div><span class="contact-link-icon">↗</span></a>
                    <a href="#" class="contact-link"><div><div class="contact-link-key">Instagram</div><div class="contact-link-val">@metehankiran</div></div><span class="contact-link-icon">↗</span></a>
                    <a href="tel:+905000000000" class="contact-link"><div><div class="contact-link-key">Telefon</div><div class="contact-link-val">+90 5XX XXX XX XX</div></div><span class="contact-link-icon">☎</span></a>
                </div>
            </div>
            <form class="form" data-mk-form>
                <h3>Hızlıca yaz</h3>
                <div class="form-row"><label for="contact-name">Adın</label><input id="contact-name" type="text" name="name" class="input" placeholder="Adınız" /></div>
                <div class="form-row"><label for="contact-email">Email</label><input id="contact-email" type="email" name="email" class="input" placeholder="siz@example.com" /></div>
                <div class="form-row">
                    <label for="contact-project-type">Proje türü</label>
                    <select id="contact-project-type" name="project_type" class="input">
                        <option>Web uygulaması / SaaS</option>
                        <option>API / Backend</option>
                        <option>Kurumsal site</option>
                        <option>CRM / Dahili araç</option>
                        <option>Diğer</option>
                    </select>
                </div>
                <div class="form-row"><label for="contact-message">Mesaj</label><textarea id="contact-message" name="message" rows="5" class="input" placeholder="Projeyi anlatabilir misin..." style="resize:vertical;"></textarea></div>
                <button type="submit" class="btn btn-primary">Gönder →</button>
            </form>
        </div>
    </div></section>
@endsection
