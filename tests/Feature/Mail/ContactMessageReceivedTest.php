<?php

declare(strict_types=1);

use App\Mail\ContactMessageReceived;
use App\Models\Contact;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the contact details, subject label and admin link', function () {
    $contact = Contact::factory()->create([
        'name' => 'Selin Akın',
        'email' => 'selin@example.com',
        'phone' => '+90 555 000 00 00',
        'subject' => 'Proje Teklifi',
        'message' => 'Yeni bir e-ticaret projesi için görüşmek istiyorum.',
    ]);

    $mail = new ContactMessageReceived($contact);

    $mail->assertHasSubject('Yeni iletişim mesajı: Proje Teklifi')
        ->assertHasReplyTo('selin@example.com', 'Selin Akın')
        ->assertSeeInHtml('Selin Akın')
        ->assertSeeInHtml('+90 555 000 00 00')
        ->assertSeeInHtml('Proje Teklifi')
        ->assertSeeInHtml('e-ticaret projesi')
        ->assertSeeInHtml('/contacts/'.$contact->id.'/edit');
});

it('is queued rather than sent inline', function () {
    expect(new ContactMessageReceived(Contact::factory()->create()))
        ->toBeInstanceOf(ShouldQueue::class);
});
