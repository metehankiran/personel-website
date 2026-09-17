<?php

use App\Filament\Resources\Services\Pages\CreateService;
use App\Filament\Resources\Services\Pages\EditService;
use App\Filament\Resources\Services\Pages\ListServices;
use App\Models\Service;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListServices::class)->assertOk();
});

test('services appear in the table', function () {
    $services = Service::factory()->count(3)->create();

    Livewire::test(ListServices::class)
        ->assertCanSeeTableRecords($services);
});

test('service can be created with features', function () {
    Livewire::test(CreateService::class)
        ->fillForm([
            'title' => 'Web Geliştirme',
            'description' => 'Modern web uygulamaları geliştiriyorum.',
            'features' => ['API geliştirme', 'Frontend', 'Deployment'],
            'pricing' => 'Aylık retainer',
            'duration' => '4 hafta',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $service = Service::firstWhere('title', 'Web Geliştirme');

    expect($service)->not->toBeNull();
    expect($service->features)->toBe(['API geliştirme', 'Frontend', 'Deployment']);
    expect($service->pricing)->toBe('Aylık retainer');
});

test('title is required', function () {
    Livewire::test(CreateService::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('description is required', function () {
    Livewire::test(CreateService::class)
        ->fillForm(['description' => null])
        ->call('create')
        ->assertHasFormErrors(['description' => 'required']);
});

test('pricing is required', function () {
    Livewire::test(CreateService::class)
        ->fillForm(['pricing' => null])
        ->call('create')
        ->assertHasFormErrors(['pricing' => 'required']);
});

test('service can be edited', function () {
    $service = Service::factory()->create(['title' => 'Old']);

    Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($service->refresh()->title)->toBe('New');
});

test('service can be deleted from edit page', function () {
    $service = Service::factory()->create();

    Livewire::test(EditService::class, ['record' => $service->getRouteKey()])
        ->callAction(DeleteAction::class);

    // Services are soft deleted so contact messages keep their reference.
    $this->assertSoftDeleted('services', ['id' => $service->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Service::factory()->create(['sort_order' => 1]);
    $b = Service::factory()->create(['sort_order' => 2]);

    Livewire::test(ListServices::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
