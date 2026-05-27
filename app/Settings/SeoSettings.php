<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SeoSettings extends Settings
{
    public ?string $meta_description;

    public ?string $og_image_path;

    public ?string $google_analytics_id;

    public ?string $google_search_console_id;

    public static function group(): string
    {
        return 'seo';
    }
}
