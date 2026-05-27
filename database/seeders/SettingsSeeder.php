<?php

namespace Database\Seeders;

use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $appName = config('app.name', 'Personel Website');

        $general = app(GeneralSettings::class);
        $general->site_title = $appName;
        $general->site_description = 'Bağımsız full-stack developer. Laravel, Vue.js ve .NET Core.';
        $general->author_name = $appName;
        $general->author_email = "hello@{$appName}.com";
        $general->author_phone = '+90 555 000 00 00';
        $general->author_address = 'İstanbul, Türkiye';
        $general->logo_path = null;
        $general->favicon_path = null;
        $general->cv_path = null;
        $general->save();

        $seo = app(SeoSettings::class);
        $seo->meta_description = "{$appName} — Bağımsız full-stack developer. Laravel, Vue.js, .NET Core ile production-hazır ürünler.";
        $seo->og_image_path = null;
        $seo->google_analytics_id = 'G-XXXXXXXXXX';
        $seo->google_search_console_id = null;
        $seo->save();

        $social = app(SocialSettings::class);
        $social->github_url = 'https://github.com';
        $social->linkedin_url = 'https://linkedin.com';
        $social->twitter_url = 'https://x.com';
        $social->youtube_url = null;
        $social->instagram_url = 'https://instagram.com';
        $social->bluesky_url = null;
        $social->save();
    }
}
