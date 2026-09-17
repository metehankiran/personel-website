<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Active navigation groups are marked with data-nav-group="…" data-active="true"
 * on both the desktop and the mobile trigger.
 */
function activeGroups(string $html): array
{
    preg_match_all('/data-nav-group="([a-z]+)"\s+data-active="true"/', $html, $matches);

    return array_values(array_unique($matches[1]));
}

it('marks the writing group active across the blog section', function (Closure $url) {
    $html = $this->get($url())->assertOk()->getContent();

    expect(activeGroups($html))->toBe(['writing']);
})->with([
    'blog index' => [fn () => route('blog')],
    'blog post' => [fn () => route('blog.show', Post::factory()->published()->for(Category::factory())->create())],
    'blog category' => [fn () => route('blog.category', Category::factory()->create())],
    'blog tag' => [fn () => route('blog.tag', Tag::factory()->create())],
    'bookmarks' => [fn () => route('bookmarks')],
]);

it('marks the works group active across its pages and sub pages', function (Closure $url) {
    $html = $this->get($url())->assertOk()->getContent();

    expect(activeGroups($html))->toBe(['works']);
})->with([
    'projects index' => [fn () => route('projects')],
    'project detail' => [fn () => route('projects.show', Project::factory()->create())],
    'services' => [fn () => route('services')],
    'references' => [fn () => route('references')],
    'stack' => [fn () => route('stack')],
]);

it('does not mark any group active on unrelated pages', function (string $route) {
    expect(activeGroups($this->get(route($route))->getContent()))->toBe([]);
})->with(['home', 'about', 'cv', 'contact']);

it('marks the current item inside the dropdown on desktop and mobile', function () {
    $post = Post::factory()->published()->for(Category::factory())->create();

    $html = $this->get(route('blog.show', $post))->getContent();

    // Blog is current in both menus; its sibling is not.
    expect(substr_count($html, 'href="'.route('blog').'" aria-current="page"'))->toBe(2)
        ->and($html)->not->toContain('href="'.route('bookmarks').'" aria-current="page"');
});

it('marks top level links with aria-current as well', function () {
    expect(substr_count($this->get(route('about'))->getContent(), 'href="'.route('about').'" aria-current="page"'))->toBe(2);
});
