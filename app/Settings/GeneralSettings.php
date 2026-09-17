<?php

namespace App\Settings;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    /**
     * Optional logo for the dark theme; the regular logo is used when it is missing.
     */
    public ?string $logo_dark_path;

    public ?string $favicon_path;

    public ?string $cv_path;

    /**
     * File name the CV was uploaded with; the stored file itself gets a random name.
     */
    public ?string $cv_original_name;

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

    /**
     * Public URL of the uploaded CV, or null when none is uploaded.
     */
    public function cvUrl(): ?string
    {
        return filled($this->cv_path) ? Storage::url($this->cv_path) : null;
    }

    /**
     * Name the browser saves the CV under: the uploaded file name, or one
     * built from the author for CVs uploaded before names were stored.
     */
    public function cvDownloadName(): string
    {
        return $this->cv_original_name ?: Str::slug($this->author_name.' cv').'.pdf';
    }

    public static function group(): string
    {
        return 'general';
    }
}
