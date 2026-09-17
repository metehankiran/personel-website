<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Resolves user-uploaded image paths to URLs, falling back to bundled
 * defaults when the path is empty or the file is missing on disk.
 */
class Images
{
    /** @var array<string, string> */
    private const array FALLBACKS = [
        'cover' => 'images/default-cover.svg',
        'avatar' => 'images/default-avatar.svg',
        'og' => 'images/og-default.png',
        'favicon' => 'theme/favicon.svg',
    ];

    public static function url(?string $path, string $fallback = 'cover'): string
    {
        if (static::isAbsolute($path)) {
            return $path;
        }

        if (static::exists($path)) {
            return Storage::url($path);
        }

        return static::fallback($fallback);
    }

    /**
     * Open Graph images must be raster files, so the fallback is always the PNG.
     */
    public static function og(?string $path): string
    {
        return static::url($path, 'og');
    }

    public static function fallback(string $kind): string
    {
        return asset(self::FALLBACKS[$kind] ?? self::FALLBACKS['cover']);
    }

    public static function exists(?string $path): bool
    {
        if (blank($path)) {
            return false;
        }

        return Storage::disk('public')->exists($path);
    }

    /**
     * Intrinsic size of a stored image, so an <img> can reserve its box before the file arrives.
     * Raster files answer from their header; an svg from its width/height or, failing that, its viewBox.
     *
     * @return array{width: int, height: int}|null
     */
    public static function dimensions(?string $path): ?array
    {
        if (static::isAbsolute($path) || ! static::exists($path)) {
            return null;
        }

        $disk = Storage::disk('public');

        if (Str::endsWith(Str::lower($path), '.svg')) {
            return static::svgDimensions((string) $disk->get($path));
        }

        $size = @getimagesizefromstring((string) $disk->get($path));

        return $size ? ['width' => $size[0], 'height' => $size[1]] : null;
    }

    /**
     * @return array{width: int, height: int}|null
     */
    private static function svgDimensions(string $svg): ?array
    {
        if (preg_match('/<svg\b[^>]*>/i', $svg, $tag) !== 1) {
            return null;
        }

        $attribute = fn (string $name): ?string => preg_match('/\s'.$name.'\s*=\s*["\']([^"\']+)["\']/i', $tag[0], $match) === 1 ? $match[1] : null;
        $pixels = fn (?string $value): ?float => $value !== null && preg_match('/^\d+(\.\d+)?(px)?$/', trim($value)) === 1 ? (float) $value : null;

        [$width, $height] = [$pixels($attribute('width')), $pixels($attribute('height'))];

        if (! $width || ! $height) {
            $box = preg_split('/[\s,]+/', trim((string) $attribute('viewBox')));
            [$width, $height] = count($box) === 4 ? [(float) $box[2], (float) $box[3]] : [null, null];
        }

        return $width > 0 && $height > 0 ? ['width' => (int) round($width), 'height' => (int) round($height)] : null;
    }

    private static function isAbsolute(?string $path): bool
    {
        return filled($path) && (Str::startsWith($path, ['http://', 'https://', '//', '/']));
    }
}
