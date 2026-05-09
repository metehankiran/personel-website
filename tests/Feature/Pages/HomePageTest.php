<?php

declare(strict_types=1);

it('renders the home page with a 200 status', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertViewIs('pages.home');
});

it('extends the app layout including header and footer', function () {
    $response = $this->get(route('home'));

    $response->assertSee('site-header', escape: false);
    $response->assertSee('site-footer', escape: false);
});

it('loads the theme stylesheet and scripts from the public theme directory', function () {
    $response = $this->get(route('home'));

    $response->assertSee(asset('theme/css/style.css'), escape: false);
    $response->assertSee(asset('theme/js/main.js'), escape: false);
});

it('renders the hero section content', function () {
    $response = $this->get(route('home'));

    $response->assertSee('full-stack', escape: false);
    $response->assertSee('Bağımsız', escape: false);
});

it('marks the home navigation link as active', function () {
    $response = $this->get(route('home'));

    $response->assertSeeInOrder([
        'nav-link active',
        'Ana sayfa',
    ], escape: false);
});
