<?php

declare(strict_types=1);

it('renders the cv page', function () {
    $this->get(route('cv'))
        ->assertOk()
        ->assertViewIs('pages.cv');
});

it('shows cv hero content', function () {
    $this->get(route('cv'))
        ->assertSee('CV', escape: false)
        ->assertSee('Metehan Kıran', escape: false);
});

it('marks cv as active in navigation', function () {
    $this->get(route('cv'))
        ->assertSeeInOrder(['nav-link active', 'CV'], escape: false);
});
