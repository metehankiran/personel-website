<?php

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\EditPage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListPages::class)->assertOk();
});

test('pages appear in the table', function () {
    $pages = Page::factory()->count(3)->create();

    Livewire::test(ListPages::class)
        ->assertCanSeeTableRecords($pages);
});

test('page can be created', function () {
    Livewire::test(CreatePage::class)
        ->fillForm([
            'title' => 'KVKK',
            'slug' => 'kvkk',
            'body' => '<p>İçerik metni.</p>',
            'is_published' => true,
            'show_footer' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $page = Page::firstWhere('slug', 'kvkk');
    expect($page)->not->toBeNull();
    expect($page->is_published)->toBeTrue();
});

test('title is required', function () {
    Livewire::test(CreatePage::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('slug must be unique', function () {
    Page::factory()->create(['slug' => 'taken']);

    Livewire::test(CreatePage::class)
        ->fillForm(['title' => 'Other', 'slug' => 'taken', 'body' => '<p>x</p>'])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('page can be edited', function () {
    $page = Page::factory()->create(['title' => 'Old']);

    Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh()->title)->toBe('New');
});

test('page can be deleted from edit page', function () {
    $page = Page::factory()->create();

    Livewire::test(EditPage::class, ['record' => $page->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});
