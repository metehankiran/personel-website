<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Settings\SocialSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('serves the search page under a Turkish url and keeps it out of the index', function () {
    expect(route('search', absolute: false))->toBe('/ara');

    $this->get(route('search'))
        ->assertOk()
        ->assertViewIs('pages.search')
        ->assertSee('<meta name="robots" content="noindex, follow">', escape: false)
        ->assertSee('action="'.route('search').'"', escape: false);
});

it('asks for a query when none is given', function () {
    $this->get(route('search'))->assertSee('Aramak için bir şey yaz');
    $this->get(route('search', ['q' => '   ']))->assertSee('Aramak için bir şey yaz');
});

it('finds posts, projects and pages by title or description', function () {
    Post::factory()->published()->for(Category::factory())->create(['title' => 'Kuyruk Rehberi', 'excerpt' => 'Horizon ile iş kuyrukları.']);
    Project::factory()->create(['title' => 'Flotaki', 'description' => 'Filo takip paneli.']);

    $this->get(route('search', ['q' => 'kuyruk']))->assertSee('Kuyruk Rehberi')->assertDontSee('Flotaki');
    $this->get(route('search', ['q' => 'horizon']))->assertSee('Kuyruk Rehberi');
    $this->get(route('search', ['q' => 'filo takip']))->assertSee('Flotaki');
    $this->get(route('search', ['q' => 'hakkımda']))->assertSee(route('about'), escape: false);
});

it('matches regardless of case and Turkish letters', function (string $query) {
    Post::factory()->published()->for(Category::factory())->create(['title' => 'İstanbul Yazılım Rehberi']);

    $this->get(route('search', ['q' => $query]))->assertSee('İstanbul Yazılım Rehberi');
})->with(['istanbul', 'İSTANBUL', 'ıstanbul', 'yazilim', 'YAZILIM']);

it('never lists drafts', function () {
    Post::factory()->for(Category::factory())->create(['title' => 'Gizli Taslak', 'is_published' => false]);

    $this->get(route('search', ['q' => 'gizli']))->assertDontSee('Gizli Taslak');
});

it('leaves quick access shortcuts out of the results', function () {
    $social = app(SocialSettings::class);
    $social->github_url = 'https://github.com/ada';
    $social->save();

    $this->get(route('search', ['q' => 'github']))
        ->assertDontSee('Hızlı erişim')
        ->assertSee('için sonuç bulunamadı');
});

it('says so when nothing matches and escapes the query', function () {
    $this->get(route('search', ['q' => '<script>alert(1)</script>']))
        ->assertOk()
        ->assertSee('için sonuç bulunamadı')
        ->assertDontSee('<script>alert(1)</script>', escape: false)
        ->assertDontSee('&amp;', escape: false);

    $this->get(route('search', ['q' => 'zzzz']))->assertSee('“zzzz” için sonuç bulunamadı');
});

it('ignores a query that is not a string', function () {
    $this->get('/ara?q[]=a')->assertOk()->assertSee('Aramak için bir şey yaz');
});
