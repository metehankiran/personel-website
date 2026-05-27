<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.author_title', null);
        $this->migrator->add('general.author_location', null);
        $this->migrator->add('general.bio', null);
        $this->migrator->add('general.hero_title', null);
        $this->migrator->add('general.hero_subtitle', null);
        $this->migrator->add('general.availability_status', null);
        $this->migrator->add('general.homepage_stats', null);
        $this->migrator->add('general.footer_text', null);
        $this->migrator->add('general.kvkk_page_slug', null);
        $this->migrator->add('general.cookie_policy_slug', null);
        $this->migrator->add('general.google_maps_url', null);
    }
};
