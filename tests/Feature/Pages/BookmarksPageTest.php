<?php

declare(strict_types=1);

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the bookmarks page', function () {
    $this->get(route('bookmarks'))
        ->assertOk()
        ->assertViewIs('pages.bookmarks');
});

it('shows the bookmarks hero content', function () {
    $this->get(route('bookmarks'))
        ->assertSee('Yer İşaretlerim', escape: false)
        ->assertSee('Faydalı linkler', escape: false);
});

it('displays bookmark categories from database', function () {
    $category = BookmarkCategory::factory()->create(['name' => 'Geliştirme', 'description' => 'Backend ve frontend için referans noktalarım.']);
    Bookmark::factory()->for($category, 'category')->create([
        'url' => 'https://laravel-news.com',
        'description' => 'Laravel ekosisteminde olan biten',
    ]);

    $this->get(route('bookmarks'))
        ->assertSee('Geliştirme', escape: false)
        ->assertSee('Backend ve frontend', escape: false)
        ->assertSee('https://laravel-news.com', escape: false)
        ->assertSee('Laravel ekosisteminde olan biten', escape: false);
});

it('displays multiple categories in order', function () {
    BookmarkCategory::factory()->create(['name' => 'Okuma', 'sort_order' => 2]);
    BookmarkCategory::factory()->create(['name' => 'Geliştirme', 'sort_order' => 1]);

    $response = $this->get(route('bookmarks'));

    $response->assertSeeInOrder(['Geliştirme', 'Okuma']);
});

it('shows empty state when no bookmarks exist', function () {
    $this->get(route('bookmarks'))
        ->assertOk();
});
