<?php

use App\Enums\LanguageLevel;
use App\Filament\Resources\Languages\Pages\CreateLanguage;
use App\Filament\Resources\Languages\Pages\EditLanguage;
use App\Filament\Resources\Languages\Pages\ListLanguages;
use App\Models\Language;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListLanguages::class)->assertOk();
});

test('languages appear in the table', function () {
    $languages = Language::factory()->count(3)->create();

    Livewire::test(ListLanguages::class)
        ->assertCanSeeTableRecords($languages);
});

test('language can be created with level enum', function () {
    Livewire::test(CreateLanguage::class)
        ->fillForm([
            'name' => 'English',
            'level' => LanguageLevel::Advanced->value,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $language = Language::firstWhere('name', 'English');
    expect($language->level)->toBe(LanguageLevel::Advanced);
});

test('name is required', function () {
    Livewire::test(CreateLanguage::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('level is required', function () {
    Livewire::test(CreateLanguage::class)
        ->fillForm(['level' => null])
        ->call('create')
        ->assertHasFormErrors(['level' => 'required']);
});

test('language can be edited', function () {
    $language = Language::factory()->create(['name' => 'Old']);

    Livewire::test(EditLanguage::class, ['record' => $language->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($language->refresh()->name)->toBe('New');
});

test('language can be deleted from edit page', function () {
    $language = Language::factory()->create();

    Livewire::test(EditLanguage::class, ['record' => $language->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('languages', ['id' => $language->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Language::factory()->create(['sort_order' => 1]);
    $b = Language::factory()->create(['sort_order' => 2]);

    Livewire::test(ListLanguages::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
