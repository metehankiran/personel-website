<?php

use Illuminate\Support\Facades\Blade;

test('errors/503 is a self-contained maintenance page', function () {
    $contents = file_get_contents(resource_path('views/errors/503.blade.php'));

    expect($contents)
        ->toStartWith('<!doctype html>')
        ->toContain('<html lang="tr">')
        ->toContain('Kısa bir bakım yapıyorum.')
        ->toContain("config('site.author.email')")
        ->toContain("config('site.author.name')")
        ->toContain('noindex')
        ->not->toContain('@extends')
        ->not->toContain('@vite')
        ->not->toContain('site-header')
        ->not->toContain('site-footer')
        ->not->toContain('href="https://')
        ->not->toContain('src="https://')
        ->not->toContain('Metehan')
        ->not->toContain('metehankiran.dev');
});

test('errors/503 pulls author info from config at render time', function () {
    config([
        'site.author.name' => 'Acme',
        'site.author.email' => 'hi@acme.test',
    ]);

    $rendered = Blade::render(file_get_contents(resource_path('views/errors/503.blade.php')));

    expect($rendered)
        ->toContain('Bakım modu — Acme')
        ->toContain('mailto:hi@acme.test')
        ->not->toContain('Metehan')
        ->not->toContain('metehankiran.dev');
});
