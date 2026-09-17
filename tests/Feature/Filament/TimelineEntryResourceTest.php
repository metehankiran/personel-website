<?php

declare(strict_types=1);

use App\Filament\Resources\TimelineEntries\Pages\CreateTimelineEntry;
use App\Filament\Resources\TimelineEntries\Pages\EditTimelineEntry;
use App\Filament\Resources\TimelineEntries\Pages\ListTimelineEntries;
use App\Filament\Resources\TimelineEntries\TimelineEntryResource;
use App\Models\TimelineEntry;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    TimelineEntry::query()->delete();

    $this->actingAs(User::factory()->create());
});

it('lives in the about navigation group with Turkish labels', function () {
    expect(TimelineEntryResource::getNavigationGroup())->toBe('Hakkımda')
        ->and(TimelineEntryResource::getModelLabel())->toBe('Zaman Çizelgesi Kaydı')
        ->and(TimelineEntryResource::getPluralModelLabel())->toBe('Zaman Çizelgesi');
});

it('lists the entries', function () {
    $entries = TimelineEntry::factory()->count(3)->create();

    Livewire::test(ListTimelineEntries::class)
        ->assertOk()
        ->assertCanSeeTableRecords($entries);
});

it('creates an entry', function () {
    Livewire::test(CreateTimelineEntry::class)
        ->fillForm([
            'period' => '2024',
            'title' => 'Karavela',
            'description' => 'Multi-tenant e-ticaret SaaS projesini sıfırdan inşa ettim.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('timeline_entries', ['period' => '2024', 'title' => 'Karavela']);
});

it('accepts free text periods such as a range or "now"', function () {
    Livewire::test(CreateTimelineEntry::class)
        ->fillForm(['period' => '2020 – 2022', 'title' => 'Ajans dönemi'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimelineEntry::sole()->period)->toBe('2020 – 2022');
});

it('requires a period and a title but not a description', function () {
    Livewire::test(CreateTimelineEntry::class)
        ->fillForm(['period' => '', 'title' => '', 'description' => null])
        ->call('create')
        ->assertHasFormErrors(['period' => 'required', 'title' => 'required'])
        ->assertHasNoFormErrors(['description']);
});

it('adds new entries to the end of the list', function () {
    TimelineEntry::factory()->create(['sort_order' => 4]);

    Livewire::test(CreateTimelineEntry::class)
        ->fillForm(['period' => '2018', 'title' => 'Kodla tanışma'])
        ->call('create');

    expect(TimelineEntry::firstWhere('title', 'Kodla tanışma')->sort_order)->toBe(5);
});

it('edits an entry', function () {
    $entry = TimelineEntry::factory()->create();

    Livewire::test(EditTimelineEntry::class, ['record' => $entry->getRouteKey()])
        ->fillForm(['title' => 'Güncel başlık'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($entry->fresh()->title)->toBe('Güncel başlık');
});

it('deletes an entry from the edit page', function () {
    $entry = TimelineEntry::factory()->create();

    Livewire::test(EditTimelineEntry::class, ['record' => $entry->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('timeline_entries', ['id' => $entry->id]);
});

it('can be reordered by dragging', function () {
    $first = TimelineEntry::factory()->create(['sort_order' => 1]);
    $second = TimelineEntry::factory()->create(['sort_order' => 2]);

    Livewire::test(ListTimelineEntries::class)
        ->call('reorderTable', [$second->getKey(), $first->getKey()]);

    expect(TimelineEntry::ordered()->pluck('id')->all())->toBe([$second->id, $first->id]);
});
