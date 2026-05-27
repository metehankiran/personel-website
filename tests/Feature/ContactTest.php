<?php

use App\Enums\ContactSubject;
use App\Models\Contact;
use Database\Seeders\ContactSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('contact can be created with valid attributes', function () {
    $contact = Contact::factory()->create();

    expect($contact)->toBeInstanceOf(Contact::class)
        ->and($contact->name)->toBeString()
        ->and($contact->email)->toBeString()
        ->and($contact->subject)->toBeInstanceOf(ContactSubject::class)
        ->and($contact->message)->toBeString()
        ->and($contact->is_read)->toBeBool();
});

test('contact is unread by default', function () {
    $contact = Contact::factory()->create();

    expect($contact->is_read)->toBeFalse();
});

test('contact can be marked as read', function () {
    $contact = Contact::factory()->create();

    $contact->update(['is_read' => true]);

    expect($contact->fresh()->is_read)->toBeTrue();
});

test('contact phone is nullable', function () {
    $contact = Contact::factory()->create(['phone' => null]);

    expect($contact->phone)->toBeNull();
});

test('contact subject is cast to enum', function () {
    $contact = Contact::factory()->create(['subject' => ContactSubject::ProjectInquiry]);

    expect($contact->fresh()->subject)->toBe(ContactSubject::ProjectInquiry);
});

test('contact factory can create read state', function () {
    $contact = Contact::factory()->read()->create();

    expect($contact->is_read)->toBeTrue();
});

test('contact seeder runs without errors', function () {
    $this->seed(ContactSeeder::class);

    expect(Contact::count())->toBeGreaterThan(0);
});
