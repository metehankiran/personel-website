<?php

declare(strict_types=1);

use App\Models\Service;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

it('shows contact info from settings', function () {
    $settings = app(GeneralSettings::class);
    $settings->author_email = 'hello@example.com';
    $settings->save();

    $this->get(route('contact'))
        ->assertSee('hello@example.com', escape: false);
});

it('shows contact subject options from the services', function () {
    Service::factory()->create(['title' => 'Proje Teklifi']);
    Service::factory()->create(['title' => 'Danışmanlık']);

    $this->get(route('contact'))
        ->assertSee('Proje Teklifi', escape: false)
        ->assertSee('Danışmanlık', escape: false)
        ->assertSee('Diğer', escape: false);
});

it('shows the contact form with required fields', function () {
    $this->get(route('contact'))
        ->assertSee('name="name"', escape: false)
        ->assertSee('name="email"', escape: false)
        ->assertSee('name="subject"', escape: false)
        ->assertSee('name="message"', escape: false);
});
