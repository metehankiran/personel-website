<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('seo.meta_description', '');
        $this->migrator->add('seo.og_image_path', '');
        $this->migrator->add('seo.google_analytics_id', '');
        $this->migrator->add('seo.google_search_console_id', '');
    }
};
