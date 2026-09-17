<?php

declare(strict_types=1);

use App\Models\Category;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

const SITE_CONTAINER = 'max-w-7xl mx-auto px-6 lg:px-12';

it('lays the cv out in the same container as the other pages', function () {
    $this->get(route('cv'))
        ->assertOk()
        ->assertSee(SITE_CONTAINER, escape: false)
        ->assertDontSee('max-w-[920px]', escape: false);
});

it('lays static pages out in the same container as the other pages', function () {
    $page = Page::factory()->published()->create();

    $this->get(route('pages.show', $page))
        ->assertOk()
        ->assertSee(SITE_CONTAINER, escape: false)
        ->assertDontSee('max-w-[920px]', escape: false);
});

it('uses the shared container on every top level page', function (string $route) {
    $this->get(route($route))->assertSee(SITE_CONTAINER, escape: false);
})->with(['home', 'about', 'services', 'references', 'stack', 'projects', 'bookmarks', 'blog', 'contact', 'cv', 'faq']);

it('calls the stack page "Teknolojiler" everywhere a visitor reads it', function () {
    $html = $this->get(route('stack'))->assertOk()->getContent();

    expect($html)->toContain('<title>Teknolojiler — ')
        ->and(substr_count($html, 'Teknolojiler'))->toBeGreaterThanOrEqual(4); // title, eyebrow, header (desktop + mobile), footer
});

it('no longer labels anything "Stack" in the navigation, footer, 404 page or search index', function () {
    $files = [
        resource_path('views/components/site-header.blade.php'),
        resource_path('views/components/site-footer.blade.php'),
        resource_path('views/errors/404.blade.php'),
        resource_path('views/pages/stack.blade.php'),
        app_path('Http/Controllers/SearchController.php'),
    ];

    foreach ($files as $file) {
        // "Full-stack" and route names such as 'stack' are fine; a capitalised standalone label is not.
        expect(preg_match('/(?<![\w-])Stack(?![\w-])/u', File::get($file)))->toBe(0, basename($file).' still says "Stack"');
    }
});

it('lists the page as "Teknolojiler" in the search index', function () {
    $titles = collect($this->getJson(route('search.index'))->json())->pluck('title');

    expect($titles)->toContain('Teknolojiler')->not->toContain('Stack');
});

it('styles links in a static page body wherever the rich editor nests them', function () {
    $page = Page::factory()->published()->create(['body' => '<p>Başvurular için <a href="mailto:a@example.com">a@example.com</a> adresine yazın.</p>']);

    $html = $this->get(route('pages.show', $page))->assertOk()->getContent();

    // "[&>a]" only matches anchors that are direct children of the body, which rich text never produces.
    expect($html)->toContain('[&_a]:underline')->not->toContain('[&>a]:');
});

it('gives static page bodies a comfortable reading width instead of a narrow column', function () {
    $page = Page::factory()->published()->create();

    $this->get(route('pages.show', $page))
        ->assertSee('max-w-[760px]', escape: false)
        ->assertDontSee('max-w-[640px]', escape: false);
});

it('never centres a page in a container narrower than the shared one', function () {
    $views = collect(File::allFiles(resource_path('views/pages')))->merge(File::allFiles(resource_path('views/errors')));

    foreach ($views as $view) {
        // A narrower "max-w-[…] mx-auto px-6" wrapper makes the content jump sideways between pages.
        expect(preg_match('/max-w-\[\d+px\] mx-auto px-6/', $view->getContents()))->toBe(0, $view->getRelativePathname().' has its own page container');
    }
});

it('starts a blog post at the same left edge as every other page and fills the row with a sidebar', function () {
    $post = Post::factory()->published()->create();
    $post->tags()->attach($tag = Tag::factory()->create());

    $html = $this->get(route('blog.show', $post))->assertOk()->getContent();

    expect($html)->not->toContain('max-w-[920px]')
        ->toContain('lg:grid-cols-[1fr_280px]')
        ->toMatch('#<aside[^>]*data-post-aside[^>]*>.*'.preg_quote(route('blog.category', $post->category), '#').'.*'.preg_quote(route('blog.tag', $tag), '#').'.*</aside>#su');
});

it('lays a project body out in the label and content grid the cv and stack pages use', function () {
    $project = Project::factory()->create(['body' => '<p>Proje anlatımı.</p>']);

    $html = $this->get(route('projects.show', $project))->assertOk()->getContent();

    expect($html)->not->toContain('max-w-[920px]')
        ->toContain('lg:grid-cols-[280px_1fr]')
        ->toContain('Proje hakkında');
});

it('fills the row beside a static page with a list of the other pages', function () {
    $page = Page::factory()->published()->create(['title' => 'Çerez Politikası']);
    $other = Page::factory()->published()->create(['title' => 'KVKK Aydınlatma Metni']);
    Page::factory()->create(['title' => 'Taslak Sayfa', 'is_published' => false]);

    $html = $this->get(route('pages.show', $page))->assertOk()->getContent();
    preg_match('#<aside[^>]*data-page-aside[^>]*>(.*?)</aside>#su', $html, $aside);

    expect($aside[1] ?? '')->toContain(route('pages.show', $other))
        ->toContain('KVKK Aydınlatma Metni')
        ->toContain(route('faq'))
        ->not->toContain('Taslak Sayfa')
        ->toMatch('#<a href="'.preg_quote(route('pages.show', $page), '#').'"[^>]*aria-current="page"#u');
});

it('runs the faq list and its answers across the full container', function () {
    Faq::factory()->create(['is_published' => true]);

    $html = $this->get(route('faq'))->assertOk()->getContent();
    preg_match('#<details.*</details>#su', $html, $list);

    // An answer capped narrower than its question row leaves a third of the row empty on desktop.
    expect($list[0] ?? '')->not->toBeEmpty()->not->toContain('max-w-[');
});

it('reserves the scrollbar gutter so short and long pages share the same content box', function () {
    expect(File::get(resource_path('css/app.css')))->toMatch('/html\s*\{[^}]*scrollbar-gutter:\s*stable/');
});

it('describes the projects in plain Turkish instead of calling them case studies', function () {
    $this->get(route('home'))->assertSee('Teslim ettiğim işler')->assertDontSee('Case study');

    expect($this->get(route('projects'))->getContent())->not->toContain('case study');
});

it('never skips a heading level', function (string $route) {
    Service::factory()->count(2)->create();
    Project::factory()->count(2)->create();
    Faq::factory()->count(2)->create(['is_published' => true]);
    Testimonial::factory()->create();
    Post::factory()->published()->for(Category::factory())->create();

    preg_match_all('/<h([1-6])\\b/', $this->get(route($route))->assertOk()->getContent(), $matches);
    $levels = array_map(intval(...), $matches[1]);

    expect($levels[0] ?? null)->toBe(1, "[{$route}] does not open with an h1");

    foreach ($levels as $index => $level) {
        $previous = $levels[$index - 1] ?? 1;

        expect($level)->toBeLessThanOrEqual($previous + 1, "[{$route}] jumps from h{$previous} to h{$level}: ".implode(' ', $levels));
    }
})->with(['home', 'about', 'services', 'projects', 'references', 'stack', 'blog', 'cv', 'contact', 'faq', 'bookmarks', 'search']);
