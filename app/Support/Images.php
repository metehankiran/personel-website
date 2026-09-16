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

    private static function isAbsolute(?string $path): bool
    {
        return filled($path) && (Str::startsWith($path, ['http://', 'https://', '//', '/']));
    }
}
