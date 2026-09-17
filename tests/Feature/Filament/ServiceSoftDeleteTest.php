<?php

declare(strict_types=1);

use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Contact;
use App\Models\Service;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('soft deletes a service from the table and keeps its messages', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);
    $contact = Contact::factory()->create(['service_id' => $service->id, 'subject' => 'Web Geliştirme']);

    Livewire::test(ListServices::class)
        ->callAction(TestAction::make(DeleteAction::class)->table($service));

    expect($service->fresh()->trashed())->toBeTrue()
        ->and($contact->fresh())->not->toBeNull()
        ->and($contact->fresh()->service_id)->toBe($service->id);
});

it('hides deleted services by default and lists them with the trashed filter', function () {
    $active = Service::factory()->create();
    $deleted = Service::factory()->create();
    $deleted->delete();

    Livewire::test(ListServices::class)
        ->assertCanSeeTableRecords([$active])
        ->assertCanNotSeeTableRecords([$deleted])
        ->filterTable('trashed', false)
        ->assertCanSeeTableRecords([$deleted])
        ->assertCanNotSeeTableRecords([$active]);
});

it('restores a deleted service', function () {
    $service = Service::factory()->create();
    $service->delete();

    Livewire::test(ListServices::class)
        ->filterTable('trashed', false)
        ->callAction(TestAction::make(RestoreAction::class)->table($service));

    expect($service->fresh()->trashed())->toBeFalse();
});

it('can open a deleted service and offers restore but never a permanent delete', function () {
    $service = Service::factory()->create();
    $service->delete();

    Livewire::test(EditService::class, ['record' => $service->id])
        ->assertOk()
        ->assertActionVisible(RestoreAction::class)
        ->assertActionDoesNotExist(ForceDeleteAction::class);
});

it('shows the stored subject of a message even after its service is deleted', function () {
    $service = Service::factory()->create(['title' => 'Web Geliştirme']);
    $contact = Contact::factory()->create(['service_id' => $service->id, 'subject' => 'Web Geliştirme']);

    $service->delete();

    Livewire::test(ListContacts::class)
        ->assertCanSeeTableRecords([$contact])
        ->assertTableColumnStateSet('subject', 'Web Geliştirme', $contact);
});

it('filters messages by service, including deleted services', function () {
    $service = Service::factory()->create();
    $about = Contact::factory()->create(['service_id' => $service->id]);
    $other = Contact::factory()->create(['service_id' => null]);

    $service->delete();

    Livewire::test(ListContacts::class)
        ->filterTable('service_id', $service->id)
        ->assertCanSeeTableRecords([$about])
        ->assertCanNotSeeTableRecords([$other]);
});
