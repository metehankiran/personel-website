<?php

namespace App\Providers;

use App\Models\Page;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::unguard();

        // Only the header and the pages sidebar list the pages, so they are not queried for every partial.
        View::composer(['components.site-header', 'components.pages-aside'], function ($view) {
            $view->with('menuPages', Page::published()
                ->get(['title', 'slug'])
                // Sort in PHP: database collations disagree on Turkish letters (SQLite orders bytewise).
                ->sortBy(fn (Page $page): string => Str::ascii(Str::lower($page->title)))
                ->values());
        });

        View::composer('*', function ($view) {
            $view->with('general', app(GeneralSettings::class));
            $view->with('seo', app(SeoSettings::class));
            $view->with('social', app(SocialSettings::class));
        });
    }
}
