<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Faq;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Settings\AboutSettings;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Schema.org structured data (JSON-LD). Every node refers to the site owner
 * through a stable @id, so search and answer engines see one connected entity
 * instead of unrelated fragments.
 */
class Schema
{
    private const string LANGUAGE = 'tr-TR';

    /**
     * Render nodes as a JSON-LD script tag; several nodes are wrapped in a @graph.
     *
     * @param  array<string, mixed>  ...$nodes
     */
    public static function script(array ...$nodes): HtmlString
    {
        $nodes = array_values(array_filter(array_map(static::clean(...), $nodes)));

        if ($nodes === []) {
            return new HtmlString('');
        }

        $data = count($nodes) === 1
            ? ['@context' => 'https://schema.org', ...$nodes[0]]
            : ['@context' => 'https://schema.org', '@graph' => $nodes];

        // JSON_HEX_TAG keeps a "</script>" inside the content from closing the tag.
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_THROW_ON_ERROR);

        return new HtmlString('<script type="application/ld+json">'.$json.'</script>');
    }

    /**
     * @return array<string, mixed>
     */
    public static function website(): array
    {
        $general = app(GeneralSettings::class);

        return [
            '@type' => 'WebSite',
            '@id' => static::id('website'),
            'url' => Seo::siteUrl(),
            'name' => $general->site_title ?: config('app.name'),
            'description' => $general->site_description,
            'inLanguage' => self::LANGUAGE,
            'publisher' => static::personReference(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function person(): array
    {
        $general = app(GeneralSettings::class);
        $portrait = app(AboutSettings::class)->portrait_path;

        return [
            '@type' => 'Person',
            '@id' => static::id('person'),
            'name' => $general->author_name,
            'jobTitle' => $general->author_title,
            'description' => $general->bio,
            'url' => Seo::siteUrl(),
            'email' => filled($general->author_email) ? 'mailto:'.$general->author_email : null,
            'image' => Images::exists($portrait) ? Seo::absolute(Images::url($portrait)) : null,
            'address' => filled($general->author_location)
                ? ['@type' => 'PostalAddress', 'addressLocality' => $general->author_location]
                : null,
            'sameAs' => array_values(array_column(app(SocialSettings::class)->profiles(), 'url')),
        ];
    }

    /**
     * @param  array<string, string>  $trail  label => absolute url, without the home page
     * @return array<string, mixed>
     */
    public static function breadcrumbs(array $trail): array
    {
        $trail = ['Ana sayfa' => Seo::siteUrl(), ...$trail];
        $position = 0;

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_map(
                function (string $label, string $url) use (&$position): array {
                    return ['@type' => 'ListItem', 'position' => ++$position, 'name' => $label, 'item' => $url];
                },
                array_keys($trail),
                $trail,
            )),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function profilePage(): array
    {
        return [
            '@type' => 'ProfilePage',
            'url' => Seo::route('about'),
            'inLanguage' => self::LANGUAGE,
            'mainEntity' => static::personReference(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function blogPosting(Post $post): array
    {
        $url = Seo::route('blog.show', $post);

        return [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'url' => $url,
            'mainEntityOfPage' => $url,
            'image' => Seo::absolute(Images::og($post->cover_image)),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'inLanguage' => self::LANGUAGE,
            'articleSection' => $post->category?->name,
            'keywords' => $post->tags->pluck('name')->all(),
            'wordCount' => Str::wordCount(strip_tags((string) $post->body)),
            'author' => static::personReference(),
            'publisher' => static::personReference(),
        ];
    }

    /**
     * @param  Collection<int, Service>  $services
     * @return array<string, mixed>
     */
    public static function services(Collection $services): array
    {
        if ($services->isEmpty()) {
            return [];
        }

        return [
            '@type' => 'ItemList',
            'name' => 'Hizmetler',
            'itemListElement' => $services->values()->map(fn (Service $service, int $index): array => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => [
                    '@type' => 'Service',
                    'name' => $service->title,
                    'description' => $service->description,
                    'provider' => static::personReference(),
                ],
            ])->all(),
        ];
    }

    /**
     * @param  Collection<int, Faq>  $faqs
     * @return array<string, mixed>
     */
    public static function faqPage(Collection $faqs): array
    {
        if ($faqs->isEmpty()) {
            return [];
        }

        return [
            '@type' => 'FAQPage',
            'mainEntity' => $faqs->values()->map(fn (Faq $faq): array => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => Str::squish(strip_tags($faq->answer))],
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function project(Project $project): array
    {
        return [
            '@type' => 'CreativeWork',
            'name' => $project->title,
            'description' => $project->description,
            'url' => Seo::route('projects.show', $project),
            'image' => Seo::absolute(Images::og($project->cover_image)),
            'dateCreated' => filled($project->year) ? (string) $project->year : null,
            'keywords' => $project->stack,
            'inLanguage' => self::LANGUAGE,
            'creator' => static::personReference(),
        ];
    }

    /**
     * @return array{'@id': string}
     */
    private static function personReference(): array
    {
        return ['@id' => static::id('person')];
    }

    private static function id(string $fragment): string
    {
        return Seo::siteUrl().'#'.$fragment;
    }

    /**
     * Drop nulls, empty strings and empty arrays so no half-filled property is published.
     *
     * @param  array<array-key, mixed>  $node
     * @return array<array-key, mixed>
     */
    private static function clean(array $node): array
    {
        $cleaned = [];

        foreach ($node as $key => $value) {
            if (is_array($value)) {
                $value = static::clean($value);
            }

            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $cleaned[$key] = $value;
        }

        return array_is_list($node) ? array_values($cleaned) : $cleaned;
    }
}
