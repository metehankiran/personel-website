<?php

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListCategories::class)->assertOk();
});

test('categories appear in the table', function () {
    $categories = Category::factory()->count(3)->create();

    Livewire::test(ListCategories::class)
        ->assertCanSeeTableRecords($categories);
});

test('category can be created without parent', function () {
    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('categories', [
        'name' => 'Laravel',
        'slug' => 'laravel',
        'parent_id' => null,
    ]);
});

test('category can be created with parent', function () {
    $parent = Category::factory()->create();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'Eloquent',
            'slug' => 'eloquent',
            'parent_id' => $parent->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $child = Category::firstWhere('slug', 'eloquent');
    expect($child->parent_id)->toBe($parent->id);
});

test('name is required', function () {
    Livewire::test(CreateCategory::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('slug must be unique', function () {
    Category::factory()->create(['slug' => 'taken']);

    Livewire::test(CreateCategory::class)
        ->fillForm(['name' => 'Other', 'slug' => 'taken'])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('category can be edited', function () {
    $category = Category::factory()->create(['name' => 'Old']);

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh()->name)->toBe('New');
});

test('category can be deleted from edit page', function () {
    $category = Category::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Category::factory()->create(['sort_order' => 1]);
    $b = Category::factory()->create(['sort_order' => 2]);

    Livewire::test(ListCategories::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
