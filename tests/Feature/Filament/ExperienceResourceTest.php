<?php

use App\Filament\Resources\Experiences\Pages\CreateExperience;
use App\Filament\Resources\Experiences\Pages\EditExperience;
use App\Filament\Resources\Experiences\Pages\ListExperiences;
use App\Models\Experience;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListExperiences::class)->assertOk();
});

test('experiences appear in the table', function () {
    $experiences = Experience::factory()->count(3)->create();

    Livewire::test(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences);
});

test('experience can be created with end date', function () {
    Livewire::test(CreateExperience::class)
        ->fillForm([
            'title' => 'Senior Backend Developer',
            'company' => 'Acme Inc.',
            'description' => 'Laravel ekibi liderliği.',
            'start_date' => '2020-01-01',
            'end_date' => '2023-12-31',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('experiences', [
        'title' => 'Senior Backend Developer',
        'company' => 'Acme Inc.',
    ]);
});

test('experience can be created with null end date (current role)', function () {
    Livewire::test(CreateExperience::class)
        ->fillForm([
            'title' => 'Lead Engineer',
            'company' => 'Current Co.',
            'start_date' => '2024-01-01',
            'end_date' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $experience = Experience::firstWhere('company', 'Current Co.');
    expect($experience->end_date)->toBeNull();
});

test('title is required', function () {
    Livewire::test(CreateExperience::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('company is required', function () {
    Livewire::test(CreateExperience::class)
        ->fillForm(['company' => null])
        ->call('create')
        ->assertHasFormErrors(['company' => 'required']);
});

test('start_date is required', function () {
    Livewire::test(CreateExperience::class)
        ->fillForm(['start_date' => null])
        ->call('create')
        ->assertHasFormErrors(['start_date' => 'required']);
});

test('experience can be edited', function () {
    $experience = Experience::factory()->create(['title' => 'Old']);

    Livewire::test(EditExperience::class, ['record' => $experience->getRouteKey()])
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($experience->refresh()->title)->toBe('New');
});

test('experience can be deleted from edit page', function () {
    $experience = Experience::factory()->create();

    Livewire::test(EditExperience::class, ['record' => $experience->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('experiences', ['id' => $experience->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Experience::factory()->create(['sort_order' => 1]);
    $b = Experience::factory()->create(['sort_order' => 2]);

    Livewire::test(ListExperiences::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
