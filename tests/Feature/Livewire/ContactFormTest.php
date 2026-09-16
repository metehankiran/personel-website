<?php

declare(strict_types=1);

use App\Enums\ContactSubject;
use App\Livewire\ContactForm;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('is rendered on the contact page', function () {
    $this->get(route('contact'))
        ->assertSeeLivewire(ContactForm::class);
});

it('submits the form without a page reload and stores the message', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('phone', '+90 555 000 00 00')
        ->set('subject', ContactSubject::ProjectInquiry->value)
        ->set('message', 'Merhaba, bir proje hakkında konuşmak istiyorum.')
        ->set('kvkk_consent', true)
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('sent', true)
        ->assertSet('name', '')
        ->assertSet('message', '')
        ->assertSee('Mesajın ulaştı')
        ->assertNoRedirect();

    $this->assertDatabaseHas('contacts', [
        'email' => 'test@example.com',
        'subject' => 'project_inquiry',
        'name' => 'Test User',
    ]);
});

it('validates required fields', function () {
    Livewire::test(ContactForm::class)
        ->call('send')
        ->assertHasErrors(['name', 'email', 'subject', 'message'])
        ->assertSet('sent', false);

    $this->assertDatabaseCount('contacts', 0);
});

it('validates the subject against the enum', function () {
    Livewire::test(ContactForm::class)
        ->set('name', 'Test')
        ->set('email', 'test@example.com')
        ->set('subject', 'invalid_subject')
        ->set('message', 'Test mesaj')
        ->call('send')
        ->assertHasErrors(['subject']);
});

it('requires kvkk consent only when a kvkk page is configured', function () {
    $settings = app(GeneralSettings::class);
    $settings->kvkk_page_slug = 'kvkk';
    $settings->save();

    Livewire::test(ContactForm::class)
        ->set('name', 'Test')
        ->set('email', 'test@example.com')
        ->set('subject', ContactSubject::Other->value)
        ->set('message', 'Test mesaj')
        ->set('kvkk_consent', false)
        ->call('send')
        ->assertHasErrors(['kvkk_consent']);

    $settings->kvkk_page_slug = null;
    $settings->save();

    Livewire::test(ContactForm::class)
        ->set('name', 'Test')
        ->set('email', 'test@example.com')
        ->set('subject', ContactSubject::Other->value)
        ->set('message', 'Test mesaj')
        ->call('send')
        ->assertHasNoErrors()
        ->assertSet('sent', true);
});

it('shows a loading state on the submit button', function () {
    Livewire::test(ContactForm::class)
        ->assertSee('wire:loading', escape: false)
        ->assertSee('Gönderiliyor');
});

it('can start a new message after a successful send', function () {
    Livewire::test(ContactForm::class)
        ->set('sent', true)
        ->call('startOver')
        ->assertSet('sent', false);
});

it('shows validation messages in Turkish', function () {
    $settings = app(GeneralSettings::class);
    $settings->kvkk_page_slug = 'kvkk';
    $settings->save();

    Livewire::test(ContactForm::class)
        ->set('email', 'not-an-email')
        ->call('send')
        ->assertSee('Ad alanı zorunludur.')
        ->assertSee('E-posta alanı geçerli bir e-posta adresi olmalıdır.')
        ->assertSee('Konu alanı zorunludur.')
        ->assertSee('Mesaj alanı zorunludur.')
        ->assertSee('KVKK onayı alanı kabul edilmelidir.')
        ->assertDontSee('validation.');
});
