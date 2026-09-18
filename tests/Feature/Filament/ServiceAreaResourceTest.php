<?php

declare(strict_types=1);

use App\Filament\Resources\ServiceAreas\Pages\CreateServiceArea;
use App\Filament\Resources\ServiceAreas\Pages\EditServiceArea;
use App\Filament\Resources\ServiceAreas\Pages\ListServiceAreas;
use App\Filament\Resources\ServiceAreas\ServiceAreaResource;
use App\Models\ServiceArea;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    ServiceArea::query()->delete();

    $this->actingAs(User::factory()->create());
});

it('lives in the about navigation group with Turkish labels', function () {
    expect(ServiceAreaResource::getNavigationGroup())->toBe('Hakkımda')
        ->and(ServiceAreaResource::getModelLabel())->toBe('Hizmet Bölgesi')
        ->and(ServiceAreaResource::getPluralModelLabel())->toBe('Hizmet Bölgeleri');
});

it('lists the areas', function () {
    $areas = ServiceArea::factory()->count(3)->create();

    Livewire::test(ListServiceAreas::class)
        ->assertOk()
        ->assertCanSeeTableRecords($areas);
});

it('creates an area and derives the slug from its name', function () {
    Livewire::test(CreateServiceArea::class)
        ->fillForm([
            'name' => 'Çavdarhisar',
            'province' => 'Kütahya',
            'summary' => 'Aizanoi antik kenti çevresinde turizm.',
            'description' => 'Zeus Tapınağı ziyaretçileri için konaklama ve rehberlik siteleri.',
            'sectors' => ['Turizm', 'Konaklama'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $area = ServiceArea::sole();

    expect($area->slug)->toBe('cavdarhisar')
        ->and($area->sectors)->toBe(['Turizm', 'Konaklama'])
        ->and($area->is_published)->toBeTrue();
});

it('requires a name and a province but no content', function () {
    Livewire::test(CreateServiceArea::class)
        ->fillForm(['name' => '', 'province' => '', 'summary' => null, 'description' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'province' => 'required'])
        ->assertHasNoFormErrors(['summary', 'description', 'sectors']);
});

it('refuses a second area with the same name', function () {
    ServiceArea::factory()->create(['name' => 'Simav']);

    Livewire::test(CreateServiceArea::class)
        ->fillForm(['name' => 'Simav', 'province' => 'Kütahya'])
        ->call('create')
        ->assertHasFormErrors(['name' => 'unique']);
});

it('adds new areas to the end of the list', function () {
    ServiceArea::factory()->create(['sort_order' => 4]);

    Livewire::test(CreateServiceArea::class)
        ->fillForm(['name' => 'Pazarlar', 'province' => 'Kütahya'])
        ->call('create');

    expect(ServiceArea::firstWhere('name', 'Pazarlar')->sort_order)->toBe(5);
});

it('edits an area without changing its slug', function () {
    $area = ServiceArea::factory()->create(['name' => 'Merkez']);

    Livewire::test(EditServiceArea::class, ['record' => $area->getRouteKey()])
        ->fillForm(['name' => 'Kütahya Merkez'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($area->fresh()->name)->toBe('Kütahya Merkez')
        ->and($area->fresh()->slug)->toBe('merkez');
});

it('deletes an area from the edit page', function () {
    $area = ServiceArea::factory()->create();

    Livewire::test(EditServiceArea::class, ['record' => $area->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('service_areas', ['id' => $area->id]);
});

it('can be reordered by dragging', function () {
    $first = ServiceArea::factory()->create(['sort_order' => 1]);
    $second = ServiceArea::factory()->create(['sort_order' => 2]);

    Livewire::test(ListServiceAreas::class)
        ->call('reorderTable', [$second->getKey(), $first->getKey()]);

    expect(ServiceArea::ordered()->pluck('id')->all())->toBe([$second->id, $first->id]);
});
