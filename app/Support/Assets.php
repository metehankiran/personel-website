<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Builds cache-busting URLs for public files that are not fingerprinted by
 * Vite, so they can be served with long-lived cache headers.
 */
class Assets
{
    public static function versioned(string $path): string
    {
        $file = public_path($path);

        if (! is_file($file)) {
            return asset($path);
        }

        return asset($path).'?v='.filemtime($file);
    }
}
