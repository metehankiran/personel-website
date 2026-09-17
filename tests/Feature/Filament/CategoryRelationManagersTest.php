<?php

declare(strict_types=1);

use App\Filament\Resources\BookmarkCategories\BookmarkCategoryResource;
use App\Filament\Resources\BookmarkCategories\Pages\EditBookmarkCategory;
use App\Filament\Resources\BookmarkCategories\RelationManagers\BookmarksRelationManager;
use App\Filament\Resources\Bookmarks\BookmarkResource;
use App\Filament\Resources\Bookmarks\Pages\CreateBookmark;
use App\Filament\Resources\ProjectCategories\Pages\EditProjectCategory;
use App\Filament\Resources\ProjectCategories\ProjectCategoryResource;
use App\Filament\Resources\ProjectCategories\RelationManagers\ProjectsRelationManager;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('registers the relation managers on the category resources', function () {
    expect(ProjectCategoryResource::getRelations())->toContain(ProjectsRelationManager::class)
        ->and(BookmarkCategoryResource::getRelations())->toContain(BookmarksRelationManager::class);
});

it('shows the projects relation below the project category form', function () {
    $category = ProjectCategory::factory()->create();

    Livewire::test(EditProjectCategory::class, ['record' => $category->getRouteKey()])
        ->assertOk()
        ->assertSeeLivewire(ProjectsRelationManager::class);
});

it('lists only the projects of the opened category', function () {
    $category = ProjectCategory::factory()->create();
    $own = Project::factory()->count(2)->create(['category_id' => $category->id]);
    $foreign = Project::factory()->create(['category_id' => ProjectCategory::factory()]);

    Livewire::test(ProjectsRelationManager::class, [
        'ownerRecord' => $category,
        'pageClass' => EditProjectCategory::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($own)
        ->assertCanNotSeeTableRecords([$foreign])
        ->assertCountTableRecords(2);
});

it('shows the bookmarks relation below the bookmark category form', function () {
    $category = BookmarkCategory::factory()->create();

    Livewire::test(EditBookmarkCategory::class, ['record' => $category->getRouteKey()])
        ->assertOk()
        ->assertSeeLivewire(BookmarksRelationManager::class);
});

it('lists only the bookmarks of the opened category', function () {
    $category = BookmarkCategory::factory()->create();
    $own = Bookmark::factory()->count(3)->create(['category_id' => $category->id]);
    $foreign = Bookmark::factory()->create(['category_id' => BookmarkCategory::factory()]);

    Livewire::test(BookmarksRelationManager::class, [
        'ownerRecord' => $category,
        'pageClass' => EditBookmarkCategory::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords($own)
        ->assertCanNotSeeTableRecords([$foreign])
        ->assertCountTableRecords(3);
});

it('titles the relation tables in Turkish and hides the redundant category column', function (string $manager, string $owner, string $page, string $title) {
    $component = Livewire::test($manager, [
        'ownerRecord' => $owner::factory()->create(),
        'pageClass' => $page,
    ]);

    expect($manager::getTitle($component->instance()->getOwnerRecord(), $page))->toBe($title);

    $component->assertTableColumnHidden('category.name');
})->with([
    'projects' => [ProjectsRelationManager::class, ProjectCategory::class, EditProjectCategory::class, 'Projeler'],
    'bookmarks' => [BookmarksRelationManager::class, BookmarkCategory::class, EditBookmarkCategory::class, 'Yer İmleri'],
]);

it('links the create action to the related create page with the category preselected', function () {
    $projectCategory = ProjectCategory::factory()->create();
    $bookmarkCategory = BookmarkCategory::factory()->create();

    Livewire::test(ProjectsRelationManager::class, ['ownerRecord' => $projectCategory, 'pageClass' => EditProjectCategory::class])
        ->assertActionHasUrl(TestAction::make(CreateAction::class)->table(), ProjectResource::getUrl('create', ['category_id' => $projectCategory->id]));

    Livewire::test(BookmarksRelationManager::class, ['ownerRecord' => $bookmarkCategory, 'pageClass' => EditBookmarkCategory::class])
        ->assertActionHasUrl(TestAction::make(CreateAction::class)->table(), BookmarkResource::getUrl('create', ['category_id' => $bookmarkCategory->id]));
});

it('preselects the category on the create pages from the query string', function () {
    $projectCategory = ProjectCategory::factory()->create();
    $bookmarkCategory = BookmarkCategory::factory()->create();

    Livewire::withQueryParams(['category_id' => $projectCategory->id])
        ->test(CreateProject::class)
        ->assertSchemaStateSet(['category_id' => $projectCategory->id]);

    Livewire::withQueryParams(['category_id' => $bookmarkCategory->id])
        ->test(CreateBookmark::class)
        ->assertSchemaStateSet(['category_id' => $bookmarkCategory->id]);
});

it('ignores a category in the query string that does not exist', function () {
    Livewire::withQueryParams(['category_id' => 999999])
        ->test(CreateProject::class)
        ->assertSchemaStateSet(['category_id' => null]);
});
