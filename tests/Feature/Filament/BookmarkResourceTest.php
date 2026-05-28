<?php

use App\Filament\Resources\Bookmarks\Pages\CreateBookmark;
use App\Filament\Resources\Bookmarks\Pages\EditBookmark;
use App\Filament\Resources\Bookmarks\Pages\ListBookmarks;
use App\Models\Bookmark;
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
    Livewire::test(ListBookmarks::class)->assertOk();
});

test('bookmarks appear in the table', function () {
    $bookmarks = Bookmark::factory()->count(3)->create();

    Livewire::test(ListBookmarks::class)
        ->assertCanSeeTableRecords($bookmarks);
});

test('bookmark can be created', function () {
    $category = BookmarkCategory::factory()->create();

    Livewire::test(CreateBookmark::class)
        ->fillForm([
            'category_id' => $category->id,
            'url' => 'https://laravel.com',
            'description' => 'PHP framework.',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $bookmark = Bookmark::firstWhere('url', 'https://laravel.com');
    expect($bookmark)->not->toBeNull();
    expect($bookmark->category_id)->toBe($category->id);
});

test('category is required', function () {
    Livewire::test(CreateBookmark::class)
        ->fillForm(['category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);
});

test('url is required', function () {
    Livewire::test(CreateBookmark::class)
        ->fillForm(['url' => null])
        ->call('create')
        ->assertHasFormErrors(['url' => 'required']);
});

test('bookmark can be edited', function () {
    $bookmark = Bookmark::factory()->create(['url' => 'https://old.test']);

    Livewire::test(EditBookmark::class, ['record' => $bookmark->getRouteKey()])
        ->fillForm(['url' => 'https://new.test'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($bookmark->refresh()->url)->toBe('https://new.test');
});

test('bookmark can be deleted from edit page', function () {
    $bookmark = Bookmark::factory()->create();

    Livewire::test(EditBookmark::class, ['record' => $bookmark->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('bookmarks', ['id' => $bookmark->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Bookmark::factory()->create(['sort_order' => 1]);
    $b = Bookmark::factory()->create(['sort_order' => 2]);

    Livewire::test(ListBookmarks::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
