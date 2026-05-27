<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_title;

    public ?string $site_description;

    public string $author_name;

    public ?string $author_email;

    public ?string $author_phone;

    public ?string $author_address;

    public ?string $logo_path;

    public ?string $favicon_path;

    public ?string $cv_path;

    public ?string $author_title;

    public ?string $author_location;

    public ?string $bio;

    public ?string $hero_title;

    public ?string $hero_subtitle;

    public ?string $availability_status;

    public ?array $homepage_stats;

    public ?string $footer_text;

    public ?string $kvkk_page_slug;

    public ?string $cookie_policy_slug;

    public ?string $google_maps_url;

    public static function group(): string
    {
        return 'general';
    }
}
