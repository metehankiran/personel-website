<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the about page', function () {
    $this->get(route('about'))
        ->assertOk()
        ->assertViewIs('pages.about');
});

it('shows about hero content', function () {
    $this->get(route('about'))
        ->assertSee('Hakkımda', escape: false)
        ->assertSee('5 yıldır kod yazıyor', escape: false);
});

it('marks about as active in navigation', function () {
    $this->get(route('about'))
        ->assertSeeInOrder(['nav-link active', 'Hakkımda'], escape: false);
});
