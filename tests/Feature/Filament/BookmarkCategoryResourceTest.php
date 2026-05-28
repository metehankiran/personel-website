<?php

use App\Filament\Resources\BookmarkCategories\Pages\CreateBookmarkCategory;
use App\Filament\Resources\BookmarkCategories\Pages\EditBookmarkCategory;
use App\Filament\Resources\BookmarkCategories\Pages\ListBookmarkCategories;
use App\Models\BookmarkCategory;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListBookmarkCategories::class)->assertOk();
});

test('categories appear in the table', function () {
    $categories = BookmarkCategory::factory()->count(3)->create();

    Livewire::test(ListBookmarkCategories::class)
        ->assertCanSeeTableRecords($categories);
});

test('category can be created', function () {
    Livewire::test(CreateBookmarkCategory::class)
        ->fillForm([
            'name' => 'Tools',
            'description' => 'Geliştirme araçları.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('bookmark_categories', ['name' => 'Tools']);
});

test('name is required', function () {
    Livewire::test(CreateBookmarkCategory::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('category can be edited', function () {
    $category = BookmarkCategory::factory()->create(['name' => 'Old']);

    Livewire::test(EditBookmarkCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh()->name)->toBe('New');
});

test('category can be deleted from edit page', function () {
    $category = BookmarkCategory::factory()->create();

    Livewire::test(EditBookmarkCategory::class, ['record' => $category->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('bookmark_categories', ['id' => $category->id]);
});

test('table is reorderable by sort_order', function () {
    $a = BookmarkCategory::factory()->create(['sort_order' => 1]);
    $b = BookmarkCategory::factory()->create(['sort_order' => 2]);

    Livewire::test(ListBookmarkCategories::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
