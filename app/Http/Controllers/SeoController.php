<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\BookmarkCategory;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceArea;
use App\Models\Skill;
use App\Models\Tag;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Settings\GeneralSettings;
use App\Support\Images;
use App\Support\Seo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\LaravelSettings\Models\SettingsProperty;

/**
 * Files that crawlers and feed readers fetch instead of people: sitemap.xml, robots.txt, llms.txt and feed.xml.
 */
class SeoController extends Controller
{
    private const int FEED_LIMIT = 20;

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

    /**
     * Summaries only: the full post stays on the site, the feed just announces it.
     */
    public function feed(GeneralSettings $settings): Response
    {
        return response()
            ->view('seo.feed', [
                'title' => $settings->site_title ?: config('app.name'),
                'description' => Seo::description($settings->site_description, $settings->bio, $settings->site_title ?: config('app.name')),
                'posts' => Post::with('category')->published()->latest('published_at')->limit(self::FEED_LIMIT)->get(),
            ])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
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
                    ...(ServiceArea::published()->exists() ? ['Hizmet Bölgeleri' => Seo::route('service-areas')] : []),
                    'Projeler' => Seo::route('projects'),
                    'Referanslar' => Seo::route('references'),
                    'Teknolojiler' => Seo::route('stack'),
                    'CV' => Seo::route('cv'),
                    'İletişim' => Seo::route('contact'),
                    ...(Faq::published()->exists() ? ['Sıkça Sorulan Sorular' => Seo::route('faq')] : []),
                    'RSS' => Seo::route('feed'),
                ],
                'areas' => ServiceArea::published()->ordered()->get(['name', 'slug', 'summary']),
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

        return collect(self::STATIC_PAGES)
            ->map(fn (array $page): array => $entry(Seo::route($page[0]), $this->staticPageModified($page[0]), $page[1], $page[2]))
            ->when(ServiceArea::published()->exists(), fn (Collection $entries): Collection => $entries->push($entry(Seo::route('service-areas'), $this->latest(ServiceArea::published()->max('updated_at')), 'monthly', '0.8')))
            ->when(Faq::published()->exists(), fn (Collection $entries): Collection => $entries->push($entry(Seo::route('faq'), $this->latest(Faq::published()->max('updated_at')), 'monthly', '0.6')))
            ->concat(Post::published()->latest('published_at')->get()->map(fn (Post $post): array => $entry(Seo::route('blog.show', $post), $post->updated_at, 'monthly', '0.7')))
            ->concat(Project::ordered()->get()->map(fn (Project $project): array => $entry(Seo::route('projects.show', $project), $project->updated_at, 'monthly', '0.7')))
            ->concat(ServiceArea::published()->ordered()->get()->map(fn (ServiceArea $area): array => $entry(Seo::route('service-areas.show', $area), $area->updated_at, 'monthly', '0.7')))
            ->concat(Category::withPublishedPosts()->get()->map(fn (Category $category): array => $entry(Seo::route('blog.category', $category), $category->updated_at, 'weekly', '0.4')))
            ->concat(Tag::withPublishedPosts()->get()->map(fn (Tag $tag): array => $entry(Seo::route('blog.tag', $tag), $tag->updated_at, 'weekly', '0.3')))
            ->concat(Page::published()->get()->map(fn (Page $page): array => $entry(Seo::route('pages.show', $page), $page->updated_at, 'yearly', '0.3')));
    }

    /**
     * A static page is as fresh as the newest thing it shows: its records, and for pages
     * built from the panel's settings, the last time those were saved.
     */
    private function staticPageModified(string $route): ?Carbon
    {
        $settings = fn (string $group): ?string => SettingsProperty::query()->where('group', $group)->max('updated_at');

        return $this->latest(...match ($route) {
            'home' => [Post::published()->max('updated_at'), Testimonial::max('updated_at'), $settings('general')],
            'about' => [TimelineEntry::max('updated_at'), $settings('about')],
            'services' => [Service::max('updated_at')],
            'projects' => [Project::max('updated_at')],
            'references' => [Testimonial::max('updated_at'), Brand::max('updated_at')],
            'stack' => [Skill::max('updated_at')],
            'blog' => [Post::published()->max('updated_at')],
            'cv' => [Experience::max('updated_at'), Education::max('updated_at'), Language::max('updated_at'), Skill::max('updated_at')],
            'contact' => [$settings('general')],
            'bookmarks' => [Bookmark::max('updated_at'), BookmarkCategory::max('updated_at')],
            default => [],
        });
    }

    private function latest(?string ...$timestamps): ?Carbon
    {
        return collect($timestamps)->filter()->map(fn (string $timestamp): Carbon => Carbon::parse($timestamp))->max();
    }
}
