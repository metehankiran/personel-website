<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\Language;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
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
     * Language names as typed in the panel (ascii-folded, lowercase) => BCP 47 code.
     *
     * @var array<string, string>
     */
    private const array LANGUAGE_CODES = [
        'turkce' => 'tr', 'turkish' => 'tr',
        'ingilizce' => 'en', 'english' => 'en',
        'almanca' => 'de', 'german' => 'de',
        'fransizca' => 'fr', 'french' => 'fr',
        'ispanyolca' => 'es', 'spanish' => 'es',
        'italyanca' => 'it', 'italian' => 'it',
        'rusca' => 'ru', 'russian' => 'ru',
        'arapca' => 'ar', 'arabic' => 'ar',
    ];

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
            'email' => $general->author_email,
            'telephone' => $general->author_phone,
            'image' => Images::exists($portrait) ? Seo::absolute(Images::url($portrait)) : null,
            'address' => static::address($general->author_location),
            'sameAs' => array_values(array_column(app(SocialSettings::class)->profiles(), 'url')),
            ...static::career(),
        ];
    }

    /**
     * "Kütahya, TR" becomes a locality and a country; anything without a trailing
     * two-letter country code stays whole, since guessing would publish a wrong country.
     *
     * @return array<string, string>|null
     */
    private static function address(?string $location): ?array
    {
        if (blank($location)) {
            return null;
        }

        [$locality, $country] = array_map(trim(...), explode(',', $location, 2)) + [1 => null];

        return preg_match('/^[A-Z]{2}$/', (string) $country) === 1
            ? ['@type' => 'PostalAddress', 'addressLocality' => $locality, 'addressCountry' => $country]
            : ['@type' => 'PostalAddress', 'addressLocality' => trim($location)];
    }

    /**
     * What the cv says about the person: skills, languages, schools and the jobs still held.
     *
     * @return array<string, array<int, mixed>>
     */
    private static function career(): array
    {
        $currentJobs = Experience::query()->whereNull('end_date')->ordered()->get();

        return [
            'knowsAbout' => Skill::ordered()->get()
                ->flatMap(fn (Skill $skill): array => array_column($skill->items ?? [], 'name'))
                ->filter()->unique()->values()->all(),
            'knowsLanguage' => Language::ordered()->get()
                ->map(fn (Language $language): array => [
                    '@type' => 'Language',
                    'name' => $language->name,
                    'alternateName' => self::LANGUAGE_CODES[Str::lower(Str::ascii($language->name))] ?? null,
                ])->all(),
            'alumniOf' => Education::ordered()->get()
                ->map(fn (Education $education): array => ['@type' => 'EducationalOrganization', 'name' => $education->school])->all(),
            'worksFor' => $currentJobs
                ->map(fn (Experience $job): array => ['@type' => 'Organization', 'name' => $job->company])->all(),
            'hasOccupation' => $currentJobs
                ->map(fn (Experience $job): array => ['@type' => 'Occupation', 'name' => $job->title])->all(),
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
            'speakable' => static::speakable(['headline', ...(filled($post->excerpt) ? ['summary'] : [])]),
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
            'speakable' => static::speakable(['question', 'answer']),
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
     * One Review node per testimonial. Ratings are published only when the client gave one,
     * and never rolled up into an AggregateRating.
     *
     * @param  Collection<int, Testimonial>  $testimonials
     * @return array<int, array<string, mixed>>
     */
    public static function reviews(Collection $testimonials): array
    {
        return $testimonials->map(fn (Testimonial $testimonial): array => [
            '@type' => 'Review',
            'itemReviewed' => static::personReference(),
            'author' => [
                '@type' => 'Person',
                'name' => $testimonial->name,
                'jobTitle' => $testimonial->title,
                'worksFor' => filled($testimonial->company) ? ['@type' => 'Organization', 'name' => $testimonial->company] : null,
            ],
            'reviewBody' => $testimonial->body,
            'reviewRating' => $testimonial->rating
                ? ['@type' => 'Rating', 'ratingValue' => $testimonial->rating, 'bestRating' => 5, 'worstRating' => 1]
                : null,
        ])->values()->all();
    }

    /**
     * The parts of a page worth reading aloud, addressed by the data-speakable hooks in the views.
     *
     * @param  array<int, string>  $parts
     * @return array<string, mixed>
     */
    private static function speakable(array $parts): array
    {
        return [
            '@type' => 'SpeakableSpecification',
            'cssSelector' => array_map(fn (string $part): string => '[data-speakable="'.$part.'"]', $parts),
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
