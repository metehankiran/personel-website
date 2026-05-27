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

    public static function group(): string
    {
        return 'general';
    }
}
