<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Str;

/**
 * URLs that search engines and social networks read. They are always built
 * from the configured site url, so a request that arrives on another host
 * (www, an IP, a preview domain) never advertises a second copy of the site.
 */
class Seo
{
    public static function siteUrl(): string
    {
        return rtrim((string) config('app.url'), '/');
    }

    public static function canonical(): string
    {
        $path = trim(request()->path(), '/');

        return $path === '' ? static::siteUrl() : static::siteUrl().'/'.$path;
    }

    /**
     * Like route(), but rooted at the configured site url instead of the current request.
     *
     * @param  mixed  $parameters
     */
    public static function route(string $name, $parameters = []): string
    {
        return static::absolute(route($name, $parameters, absolute: false));
    }

    /**
     * A meta description from the first non-empty candidate: tags stripped,
     * whitespace collapsed and cut on a word boundary at 160 characters.
     */
    public static function description(?string ...$candidates): string
    {
        foreach ($candidates as $candidate) {
            // Put a space where block tags were, so "…</p><p>…" does not glue two words together.
            $text = Str::squish(html_entity_decode(strip_tags(str_replace('<', ' <', (string) $candidate)), ENT_QUOTES | ENT_HTML5));

            if ($text === '') {
                continue;
            }

            return mb_strlen($text) <= 160 ? $text : Str::limit($text, 157, '…', preserveWords: true);
        }

        return '';
    }

    public static function absolute(string $url): string
    {
        // asset() and route() root urls at the current request; move those onto the site url too.
        $requestRoot = rtrim(request()->root(), '/');

        if ($requestRoot !== '' && Str::startsWith($url, $requestRoot)) {
            $url = Str::after($url, $requestRoot);
        } elseif (Str::startsWith($url, ['http://', 'https://', '//'])) {
            return $url;
        }

        $path = ltrim($url, '/');

        return $path === '' ? static::siteUrl() : static::siteUrl().'/'.$path;
    }
}
