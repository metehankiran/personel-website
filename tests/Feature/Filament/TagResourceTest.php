<?php

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Models\Tag;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListTags::class)->assertOk();
});

test('tags appear in the table', function () {
    $tags = Tag::factory()->count(3)->create();

    Livewire::test(ListTags::class)
        ->assertCanSeeTableRecords($tags);
});

test('tag can be created', function () {
    Livewire::test(CreateTag::class)
        ->fillForm([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('tags', ['slug' => 'laravel']);
});

test('name is required', function () {
    Livewire::test(CreateTag::class)
        ->fillForm(['name' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});

test('slug must be unique', function () {
    Tag::factory()->create(['slug' => 'taken']);

    Livewire::test(CreateTag::class)
        ->fillForm(['name' => 'Other', 'slug' => 'taken'])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('tag can be edited', function () {
    $tag = Tag::factory()->create(['name' => 'Old']);

    Livewire::test(EditTag::class, ['record' => $tag->getRouteKey()])
        ->fillForm(['name' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($tag->refresh()->name)->toBe('New');
});

test('tag can be deleted from edit page', function () {
    $tag = Tag::factory()->create();

    Livewire::test(EditTag::class, ['record' => $tag->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Tag::factory()->create(['sort_order' => 1]);
    $b = Tag::factory()->create(['sort_order' => 2]);

    Livewire::test(ListTags::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
