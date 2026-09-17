<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.logo_dark_path', null);
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('general.logo_dark_path');
    }
};
