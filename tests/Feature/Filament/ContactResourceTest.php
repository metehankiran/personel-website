<?php

use App\Enums\ContactSubject;
use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\Contacts\Pages\EditContact;
use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Models\Contact;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListContacts::class)->assertOk();
});

test('contacts appear in the table', function () {
    $contacts = Contact::factory()->count(3)->create();

    Livewire::test(ListContacts::class)
        ->assertCanSeeTableRecords($contacts);
});

test('contact can be edited to toggle read status', function () {
    $contact = Contact::factory()->create(['is_read' => false]);

    Livewire::test(EditContact::class, ['record' => $contact->getRouteKey()])
        ->fillForm(['is_read' => true])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($contact->refresh()->is_read)->toBeTrue();
});

test('contact can be deleted from edit page', function () {
    $contact = Contact::factory()->create();

    Livewire::test(EditContact::class, ['record' => $contact->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
});

test('navigation badge counts unread contacts', function () {
    Contact::factory()->count(4)->create(['is_read' => false]);
    Contact::factory()->count(2)->create(['is_read' => true]);

    expect(ContactResource::getNavigationBadge())->toBe('4');
});

test('navigation badge is null when no unread', function () {
    Contact::factory()->count(2)->create(['is_read' => true]);

    expect(ContactResource::getNavigationBadge())->toBeNull();
});

test('subject enum is cast', function () {
    $contact = Contact::factory()->create(['subject' => ContactSubject::Consulting->value]);

    expect($contact->subject)->toBe(ContactSubject::Consulting);
});
