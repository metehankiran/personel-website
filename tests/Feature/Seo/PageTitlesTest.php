<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $general = app(GeneralSettings::class);
    $general->site_title = 'Metehan KIRAN';
    $general->save();
});

function pageTitle(string $route): string
{
    preg_match('#<title>(.*?)</title>#s', test()->get(route($route))->assertOk()->getContent(), $match);

    return html_entity_decode($match[1] ?? '', ENT_QUOTES);
}

it('says what the page offers in its title, not just its menu label', function (string $route, string $expected) {
    expect(pageTitle($route))->toBe($expected.' — Metehan KIRAN');
})->with([
    'services' => ['services', 'Freelance Laravel ve Vue.js Geliştirme'],
    'projects' => ['projects', 'Laravel, Vue.js ve .NET Core Projeleri'],
    'references' => ['references', 'Müşteri Yorumları ve Referanslar'],
    'stack' => ['stack', 'Teknolojiler: Laravel, Vue.js, .NET Core'],
    'about' => ['about', 'Hakkımda: Full Stack Web Developer'],
    'blog' => ['blog', 'Blog: Laravel ve Web Geliştirme Yazıları'],
    'contact' => ['contact', 'İletişim: Proje Teklifi ve Danışmanlık'],
    'faq' => ['faq', 'SSS: Proje Süresi, Fiyat ve Çalışma Süreci'],
]);

it('keeps every title long enough to say something and short enough not to be cut off', function () {
    foreach (['services', 'projects', 'references', 'stack', 'about', 'blog', 'contact', 'faq'] as $route) {
        $length = mb_strlen(pageTitle($route));

        expect($length)->toBeGreaterThanOrEqual(30, "[{$route}] title is too short ({$length})")
            ->toBeLessThanOrEqual(60, "[{$route}] title would be truncated in search results ({$length})");
    }
});

it('opens the listing pages with a heading people search for', function (string $route, string $heading) {
    expect($this->get(route($route))->getContent())->toMatch('#<h1[^>]*>\s*'.preg_quote($heading, '#').'\s*</h1>#u');
})->with([
    'services' => ['services', 'Freelance web geliştirme hizmetleri.'],
    'projects' => ['projects', 'Teslim ettiğim projeler.'],
    'references' => ['references', 'Müşteri yorumları ve referanslar.'],
    'blog' => ['blog', 'Yazılım üzerine yazılar.'],
]);

it('makes no claims in fixed copy that the panel cannot correct', function () {
    expect($this->get(route('projects'))->getContent())->not->toContain('5 yıllık')
        ->and($this->get(route('services'))->getContent())->not->toContain('Üç farklı şekilde');
});
