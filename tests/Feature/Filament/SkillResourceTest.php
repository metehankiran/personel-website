<?php

use App\Filament\Resources\Skills\Pages\CreateSkill;
use App\Filament\Resources\Skills\Pages\EditSkill;
use App\Filament\Resources\Skills\Pages\ListSkills;
use App\Models\Skill;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListSkills::class)->assertOk();
});

test('skills appear in the table', function () {
    $skills = Skill::factory()->count(3)->create();

    Livewire::test(ListSkills::class)
        ->assertCanSeeTableRecords($skills);
});

test('skill can be created with items', function () {
    Livewire::test(CreateSkill::class)
        ->fillForm([
            'name' => 'Backend',
            'description' => 'Sunucu tarafı yetenekler.',
            'items' => [
                ['name' => 'PHP', 'description' => 'Ana dilim', 'level' => 5],
                ['name' => 'Laravel', 'description' => 'Ana çerçevem', 'level' => 5],
            ],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $skill = Skill::firstWhere('name', 'Backend');

    expect($skill)->not->toBeNull();
    expect($skill->items)->toHaveCount(2);
    expect($skill->items[0]['name'])->toBe('PHP');
    expect($skill->items[0]['level'])->toBe(5);
});

test('name is required', function () {
    Livewire::test(CreateSkill::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('skill can be edited', function () {
    $skill = Skill::factory()->create(['name' => 'Old Group']);

    Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
        ->fillForm(['name' => 'New Group'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($skill->refresh()->name)->toBe('New Group');
});

test('skill can be deleted from edit page', function () {
    $skill = Skill::factory()->create();

    Livewire::test(EditSkill::class, ['record' => $skill->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Skill::factory()->create(['sort_order' => 1]);
    $b = Skill::factory()->create(['sort_order' => 2]);

    Livewire::test(ListSkills::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
