<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('social.github_url', '');
        $this->migrator->add('social.linkedin_url', '');
        $this->migrator->add('social.twitter_url', '');
        $this->migrator->add('social.youtube_url', '');
        $this->migrator->add('social.instagram_url', '');
        $this->migrator->add('social.bluesky_url', '');
    }
};
