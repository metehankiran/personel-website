<?php

use App\Filament\Resources\ProjectCategories\Pages\CreateProjectCategory;
use App\Filament\Resources\ProjectCategories\Pages\EditProjectCategory;
use App\Filament\Resources\ProjectCategories\Pages\ListProjectCategories;
use App\Models\ProjectCategory;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListProjectCategories::class)->assertOk();
});

test('categories appear in the table', function () {
    $categories = ProjectCategory::factory()->count(3)->create();

    Livewire::test(ListProjectCategories::class)
        ->assertCanSeeTableRecords($categories);
});

test('category can be created with manual slug', function () {
    Livewire::test(CreateProjectCategory::class)
        ->fillForm([
            'name' => 'SaaS',
            'slug' => 'saas',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('project_categories', [
        'name' => 'SaaS',
        'slug' => 'saas',
    ]);
});

test('name is required', function () {
    Livewire::test(CreateProjectCategory::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('slug must be unique', function () {
    ProjectCategory::factory()->create(['slug' => 'saas']);

    Livewire::test(CreateProjectCategory::class)
        ->fillForm([
            'name' => 'Another SaaS',
            'slug' => 'saas',
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('category can be edited', function () {
    $category = ProjectCategory::factory()->create(['name' => 'Old']);

    Livewire::test(EditProjectCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh()->name)->toBe('New');
});

test('category can be deleted from edit page', function () {
    $category = ProjectCategory::factory()->create();

    Livewire::test(EditProjectCategory::class, ['record' => $category->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('project_categories', ['id' => $category->id]);
});

test('table is reorderable by sort_order', function () {
    $a = ProjectCategory::factory()->create(['sort_order' => 1]);
    $b = ProjectCategory::factory()->create(['sort_order' => 2]);

    Livewire::test(ListProjectCategories::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
