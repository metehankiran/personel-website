<?php

declare(strict_types=1);

it('renders the contact page', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertViewIs('pages.contact');
});

it('shows contact hero content', function () {
    $this->get(route('contact'))
        ->assertSee('İletişim', escape: false)
        ->assertSee('Birlikte', escape: false);
});

it('marks contact as active in navigation', function () {
    $this->get(route('contact'))
        ->assertSeeInOrder(['nav-link active', 'İletişim'], escape: false);
});

it('exposes the contact form with required fields', function () {
    $this->get(route('contact'))
        ->assertSee('data-mk-form', escape: false)
        ->assertSee('name="name"', escape: false)
        ->assertSee('name="email"', escape: false)
        ->assertSee('name="message"', escape: false);
});
