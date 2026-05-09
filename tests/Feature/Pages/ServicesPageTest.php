<?php

declare(strict_types=1);

it('renders the services page', function () {
    $this->get(route('services'))
        ->assertOk()
        ->assertViewIs('pages.services');
});

it('shows services hero content', function () {
    $this->get(route('services'))
        ->assertSee('Hizmetler', escape: false)
        ->assertSee('Birlikte çalışmanın yolları', escape: false);
});
