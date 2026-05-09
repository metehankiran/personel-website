<?php

declare(strict_types=1);

it('renders the uses page', function () {
    $this->get(route('uses'))
        ->assertOk()
        ->assertViewIs('pages.uses');
});

it('shows uses hero content', function () {
    $this->get(route('uses'))
        ->assertSee('Uses', escape: false)
        ->assertSee('Setup ve araçlar', escape: false);
});
