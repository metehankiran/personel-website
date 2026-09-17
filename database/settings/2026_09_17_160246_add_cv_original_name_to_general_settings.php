<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.cv_original_name', null);
    }

    public function down(): void
    {
        $this->migrator->delete('general.cv_original_name');
    }
};
