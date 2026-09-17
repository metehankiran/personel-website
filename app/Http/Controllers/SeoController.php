<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Settings\GeneralSettings;
use App\Support\Images;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Files that crawlers read instead of people: sitemap.xml, robots.txt and llms.txt.
 */
class SeoController extends Controller
{
    /**
     * Static pages in the order they matter; [route name, change frequency, priority].
     *
     * @var array<int, array{0: string, 1: string, 2: string}>
     */
    private const array STATIC_PAGES = [
        ['home', 'weekly', '1.0'],
        ['about', 'monthly', '0.8'],
        ['services', 'monthly', '0.9'],
        ['projects', 'monthly', '0.8'],
        ['references', 'monthly', '0.6'],
        ['stack', 'monthly', '0.6'],
        ['blog', 'weekly', '0.8'],
        ['cv', 'monthly', '0.6'],
        ['contact', 'yearly', '0.7'],
        ['bookmarks', 'monthly', '0.4'],
    ];

    public function sitemap(): Response
    {
        return response()
            ->view('seo.sitemap', ['entries' => $this->sitemapEntries()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /livewire',
            '',
            'Sitemap: '.Seo::absolute('sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n")->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * Browsers and crawlers probe /favicon.ico no matter what the page links to.
     */
    public function favicon(GeneralSettings $settings): RedirectResponse
    {
        return redirect()->to(Seo::absolute(Images::url($settings->favicon_path, 'favicon')));
    }

    public function llms(GeneralSettings $settings): Response
    {
        return response()
            ->view('seo.llms', [
                'title' => $settings->site_title ?: config('app.name'),
                'summary' => $settings->site_description ?: $settings->bio,
                'pages' => [
                    'Hakkımda' => Seo::route('about'),
                    'Hizmetler' => Seo::route('services'),
                    'Projeler' => Seo::route('projects'),
                    'Referanslar' => Seo::route('references'),
                    'Teknolojiler' => Seo::route('stack'),
                    'CV' => Seo::route('cv'),
                    'İletişim' => Seo::route('contact'),
                ],
                'projects' => Project::ordered()->get(['title', 'slug', 'description']),
                'posts' => Post::published()->latest('published_at')->limit(30)->get(['title', 'slug', 'excerpt']),
            ])
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * @return Collection<int, array{loc: string, lastmod: ?string, changefreq: string, priority: string}>
     */
    private function sitemapEntries(): Collection
    {
        $entry = fn (string $url, ?Carbon $modified, string $frequency, string $priority): array => [
            'loc' => $url,
            'lastmod' => $modified?->toAtomString(),
            'changefreq' => $frequency,
            'priority' => $priority,
        ];

        $hasPublishedPosts = fn ($query) => $query->published();

        return collect(self::STATIC_PAGES)
            ->map(fn (array $page): array => $entry(Seo::route($page[0]), null, $page[1], $page[2]))
            ->concat(Post::published()->latest('published_at')->get()->map(fn (Post $post): array => $entry(Seo::route('blog.show', $post), $post->updated_at, 'monthly', '0.7')))
            ->concat(Project::ordered()->get()->map(fn (Project $project): array => $entry(Seo::route('projects.show', $project), $project->updated_at, 'monthly', '0.7')))
            ->concat(Category::whereHas('posts', $hasPublishedPosts)->get()->map(fn (Category $category): array => $entry(Seo::route('blog.category', $category), $category->updated_at, 'weekly', '0.4')))
            ->concat(Tag::whereHas('posts', $hasPublishedPosts)->get()->map(fn (Tag $tag): array => $entry(Seo::route('blog.tag', $tag), $tag->updated_at, 'weekly', '0.3')))
            ->concat(Page::published()->get()->map(fn (Page $page): array => $entry(Seo::route('pages.show', $page), $page->updated_at, 'yearly', '0.3')));
    }
}
