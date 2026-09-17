<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['app.url' => 'https://example.test']);
    URL::forceRootUrl('https://example.test');

    $general = app(GeneralSettings::class);
    $general->site_title = 'Ada Studio';
    $general->site_description = 'Bağımsız full-stack developer.';
    $general->author_name = 'Ada Yazar';
    $general->author_title = 'Full-stack Developer';
    $general->author_email = 'ada@example.test';
    $general->author_location = 'Kütahya, TR';
    $general->save();

    $social = app(SocialSettings::class);
    $social->github_url = 'https://github.com/ada';
    $social->linkedin_url = 'https://linkedin.com/in/ada';
    $social->save();
});

/**
 * Every JSON-LD node on the page, flattened out of any @graph wrapper and keyed by @type.
 */
function schemaNodes(string $url): Collection
{
    $html = test()->get($url)->assertOk()->getContent();

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);

    return collect($matches[1])
        ->map(function (string $json): array {
            $data = json_decode($json, true, flags: JSON_THROW_ON_ERROR);

            return $data['@graph'] ?? [$data];
        })
        ->flatten(1)
        ->keyBy('@type');
}

it('describes the site and its owner on every page', function (string $route) {
    $nodes = schemaNodes(route($route));

    expect($nodes['WebSite'])->toMatchArray([
        '@id' => 'https://example.test#website',
        'url' => 'https://example.test',
        'name' => 'Ada Studio',
        'description' => 'Bağımsız full-stack developer.',
        'inLanguage' => 'tr-TR',
        'publisher' => ['@id' => 'https://example.test#person'],
    ]);

    expect($nodes['Person'])->toMatchArray([
        '@id' => 'https://example.test#person',
        'name' => 'Ada Yazar',
        'jobTitle' => 'Full-stack Developer',
        'url' => 'https://example.test',
        'email' => 'mailto:ada@example.test',
        'sameAs' => ['https://github.com/ada', 'https://linkedin.com/in/ada'],
    ]);
})->with(['home', 'services', 'blog', 'contact']);

it('leaves empty fields out instead of printing nulls', function () {
    $general = app(GeneralSettings::class);
    $general->author_email = null;
    $general->save();

    expect(schemaNodes(route('home'))['Person'])->not->toHaveKey('email');
});

it('gives inner pages a breadcrumb trail and the home page none', function () {
    expect(schemaNodes(route('home')))->not->toHaveKey('BreadcrumbList');

    $items = schemaNodes(route('services'))['BreadcrumbList']['itemListElement'];

    expect($items)->toBe([
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Ana sayfa', 'item' => 'https://example.test'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Hizmetler', 'item' => 'https://example.test/hizmetler'],
    ]);
});

it('marks the about page as the profile of the person', function () {
    expect(schemaNodes(route('about'))['ProfilePage'])->toMatchArray([
        'url' => 'https://example.test/hakkimda',
        'mainEntity' => ['@id' => 'https://example.test#person'],
    ]);
});

it('describes a blog post as an article by the person', function () {
    $post = Post::factory()->published()->for(Category::factory()->create(['name' => 'Mimari']))
        ->hasAttached(Tag::factory()->create(['name' => 'Laravel']))
        ->create(['title' => 'İlk Yazı', 'slug' => 'ilk-yazi', 'excerpt' => 'Kısa özet.', 'published_at' => '2026-03-05 10:00:00']);

    $nodes = schemaNodes(route('blog.show', $post));

    expect($nodes['BlogPosting'])->toMatchArray([
        'headline' => 'İlk Yazı',
        'description' => 'Kısa özet.',
        'url' => 'https://example.test/blog/ilk-yazi',
        'mainEntityOfPage' => 'https://example.test/blog/ilk-yazi',
        'datePublished' => '2026-03-05T10:00:00+03:00',
        'dateModified' => $post->updated_at->toIso8601String(),
        'inLanguage' => 'tr-TR',
        'articleSection' => 'Mimari',
        'keywords' => ['Laravel'],
        'author' => ['@id' => 'https://example.test#person'],
        'publisher' => ['@id' => 'https://example.test#person'],
    ]);

    expect($nodes['BlogPosting']['image'])->toStartWith('https://example.test/')
        ->and(array_column($nodes['BreadcrumbList']['itemListElement'], 'name'))->toBe(['Ana sayfa', 'Blog', 'İlk Yazı']);
});

it('lists the services as offers of the person', function () {
    Service::factory()->create(['title' => 'Sıfırdan ürün', 'description' => 'MVP geliştirme.', 'sort_order' => 1]);
    Service::factory()->create(['title' => 'Danışmanlık', 'description' => 'Mimari inceleme.', 'sort_order' => 2]);

    $list = schemaNodes(route('services'))['ItemList'];

    expect(array_column($list['itemListElement'], 'position'))->toBe([1, 2])
        ->and($list['itemListElement'][0]['item'])->toMatchArray([
            '@type' => 'Service',
            'name' => 'Sıfırdan ürün',
            'description' => 'MVP geliştirme.',
            'provider' => ['@id' => 'https://example.test#person'],
        ]);
});

it('describes a project as a creative work', function () {
    $project = Project::factory()->create(['title' => 'Karavela', 'slug' => 'karavela', 'description' => 'E-ticaret SaaS.', 'year' => 2024, 'stack' => ['Laravel', 'Vue 3']]);

    expect(schemaNodes(route('projects.show', $project))['CreativeWork'])->toMatchArray([
        'name' => 'Karavela',
        'description' => 'E-ticaret SaaS.',
        'url' => 'https://example.test/projeler/karavela',
        'dateCreated' => '2024',
        'keywords' => ['Laravel', 'Vue 3'],
        'creator' => ['@id' => 'https://example.test#person'],
    ]);
});

it('cannot be broken out of by content that contains a script tag', function () {
    $post = Post::factory()->published()->for(Category::factory())->create(['title' => 'Kötü </script><script>alert(1)</script> başlık']);

    $html = $this->get(route('blog.show', $post))->getContent();

    expect($html)->not->toContain('</script><script>alert(1)')
        ->and(schemaNodes(route('blog.show', $post))['BlogPosting']['headline'])->toBe('Kötü </script><script>alert(1)</script> başlık');
});
