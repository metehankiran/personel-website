<?php

declare(strict_types=1);

use App\Enums\ContactSubject;
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

it('shows contact subject options from enum', function () {
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

it('submits contact form successfully', function () {
    $this->post(route('contact.send'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '+90 555 000 00 00',
        'subject' => ContactSubject::ProjectInquiry->value,
        'message' => 'Merhaba, bir proje hakkında konuşmak istiyorum.',
    ])->assertRedirect()
        ->assertSessionHas('success');

    $this->assertDatabaseHas('contacts', [
        'email' => 'test@example.com',
        'subject' => 'project_inquiry',
    ]);
});

it('validates contact form fields', function () {
    $this->post(route('contact.send'), [])
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

it('validates contact subject is a valid enum', function () {
    $this->post(route('contact.send'), [
        'name' => 'Test',
        'email' => 'test@example.com',
        'subject' => 'invalid_subject',
        'message' => 'Test mesaj',
    ])->assertSessionHasErrors('subject');
});
