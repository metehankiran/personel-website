<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.site_title', 'Metehan Kıran');
        $this->migrator->add('general.site_description', '');
        $this->migrator->add('general.author_name', 'Metehan Kıran');
        $this->migrator->add('general.author_email', '');
        $this->migrator->add('general.author_phone', '');
        $this->migrator->add('general.author_address', '');
        $this->migrator->add('general.logo_path', '');
        $this->migrator->add('general.favicon_path', '');
    }
};
