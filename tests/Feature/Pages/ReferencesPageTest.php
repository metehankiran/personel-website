<?php

declare(strict_types=1);

it('renders the references page', function () {
    $this->get(route('references'))
        ->assertOk()
        ->assertViewIs('pages.references');
});

it('shows references hero content', function () {
    $this->get(route('references'))
        ->assertSee('Referanslar', escape: false)
        ->assertSee('Birlikte çalıştığım insanlar', escape: false);
});
