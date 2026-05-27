<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::unguard();

        View::composer('*', function ($view) {
            $view->with('general', app(GeneralSettings::class));
            $view->with('social', app(SocialSettings::class));
        });
    }
}
