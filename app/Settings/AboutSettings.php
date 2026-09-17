<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AboutSettings extends Settings
{
    public ?string $heading;

    /**
     * Rich editor HTML shown as the introduction on the about page.
     */
    public ?string $body;

    public ?string $portrait_path;

    public ?string $work_mode;

    public static function group(): string
    {
        return 'about';
    }
}
