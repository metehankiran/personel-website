<?php

declare(strict_types=1);

use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makePage(array $attributes = []): Page
{
    return Page::factory()->create(['slug' => 'kvkk', 'title' => 'KVKK', ...$attributes]);
}

function makePost(array $attributes = []): Post
{
    return Post::factory()->for(Category::factory())->create(['slug' => 'ilk-yazi', ...$attributes]);
}

dataset('visibility', [
    'draft' => [['is_published' => false, 'published_at' => null], false],
    'published without a date' => [['is_published' => true, 'published_at' => null], false],
    'scheduled for the future' => [['is_published' => true, 'published_at' => '2999-01-01 00:00:00'], false],
    'date passed but switched off' => [['is_published' => false, 'published_at' => '2020-01-01 00:00:00'], false],
    'published in the past' => [['is_published' => true, 'published_at' => '2020-01-01 00:00:00'], true],
]);

it('decides whether a static page is public', function (array $state, bool $visible) {
    $page = makePage($state);

    expect($page->isPublished())->toBe($visible)
        ->and(Page::published()->whereKey($page->id)->exists())->toBe($visible);

    $this->get(route('pages.show', $page))->assertStatus($visible ? 200 : 404);
})->with('visibility');

it('decides whether a blog post is public', function (array $state, bool $visible) {
    $post = makePost($state);

    expect($post->isPublished())->toBe($visible)
        ->and(Post::published()->whereKey($post->id)->exists())->toBe($visible);

    $this->get(route('blog.show', $post))->assertStatus($visible ? 200 : 404);

    $listing = $this->get(route('blog'));
    $visible ? $listing->assertSee($post->title) : $listing->assertDontSee($post->title);
})->with('visibility');

it('lets a signed in user preview drafts and scheduled content', function () {
    $page = makePage(['is_published' => false]);
    $post = makePost(['is_published' => true, 'published_at' => '2999-01-01 00:00:00']);

    $this->actingAs(User::factory()->create());

    $this->get(route('pages.show', $page))->assertOk();
    $this->get(route('blog.show', $post))->assertOk();
});

it('makes a scheduled page public once its date arrives', function () {
    $page = makePage(['is_published' => true, 'published_at' => now()->addDay()]);

    $this->get(route('pages.show', $page))->assertNotFound();

    $this->travel(2)->days();

    $this->get(route('pages.show', $page))->assertOk();
});

it('keeps scheduled pages out of the cookie banner until they are live', function () {
    makePage(['slug' => 'cookie-policy', 'is_published' => true, 'published_at' => now()->addDay()]);

    $settings = app(GeneralSettings::class);
    $settings->cookie_policy_slug = 'cookie-policy';
    $settings->save();

    $this->get(route('home'))->assertSee('cookiePolicyUrl: null', escape: false);

    $this->travel(2)->days();

    $this->get(route('home'))->assertSee('cookiePolicyUrl: "'.route('pages.show', 'cookie-policy').'"', escape: false);
});

it('gives pages that were already published a publish date when the column is added', function () {
    DB::table('pages')->insert([
        ['title' => 'Eski yayın', 'slug' => 'eski-yayin', 'body' => 'x', 'is_published' => true, 'published_at' => null, 'created_at' => '2024-03-01 10:00:00', 'updated_at' => now()],
        ['title' => 'Eski taslak', 'slug' => 'eski-taslak', 'body' => 'x', 'is_published' => false, 'published_at' => null, 'created_at' => '2024-03-01 10:00:00', 'updated_at' => now()],
    ]);

    $migration = require collect(glob(database_path('migrations/*_backfill_published_at_on_pages.php')))->sole();
    $migration->up();

    expect(Page::firstWhere('slug', 'eski-yayin')->published_at->toDateTimeString())->toBe('2024-03-01 10:00:00')
        ->and(Page::firstWhere('slug', 'eski-taslak')->published_at)->toBeNull();
});

it('sets the publish date to now when a page is switched on in the panel', function () {
    $this->actingAs(User::factory()->create());
    $this->freezeTime();

    Livewire::test(CreatePage::class)
        ->fillForm(['title' => 'Gizlilik', 'slug' => 'gizlilik', 'body' => '<p>Metin</p>'])
        ->set('data.is_published', true)
        ->assertSchemaStateSet(['published_at' => now()->format('Y-m-d H:i:s')])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Page::firstWhere('slug', 'gizlilik')->isPublished())->toBeTrue();
});

it('requires a publish date for a page that is switched on', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreatePage::class)
        ->fillForm(['title' => 'Gizlilik', 'slug' => 'gizlilik', 'body' => '<p>Metin</p>', 'is_published' => true, 'published_at' => null])
        ->call('create')
        ->assertHasFormErrors(['published_at' => 'required_if']);
});

it('shows the publish date in the pages table', function () {
    $this->actingAs(User::factory()->create());
    $page = makePage(['is_published' => true, 'published_at' => '2026-01-05 09:30:00']);

    Livewire::test(ListPages::class)
        ->assertCanSeeTableRecords([$page])
        ->assertTableColumnExists('published_at');
});
