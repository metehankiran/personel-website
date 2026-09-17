<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SocialSettings extends Settings
{
    public ?string $github_url;

    public ?string $linkedin_url;

    public ?string $twitter_url;

    public ?string $youtube_url;

    public ?string $instagram_url;

    public ?string $bluesky_url;

    /**
     * Configured profiles in display order; platforms without a URL are left out.
     *
     * @return array<string, array{label: string, url: string}>
     */
    public function profiles(): array
    {
        return collect([
            'github' => 'GitHub',
            'linkedin' => 'LinkedIn',
            'twitter' => 'Twitter / X',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'bluesky' => 'Bluesky',
        ])
            ->map(fn (string $label, string $platform): array => ['label' => $label, 'url' => (string) $this->{$platform.'_url'}])
            ->filter(fn (array $profile): bool => filled($profile['url']))
            ->all();
    }

    public static function group(): string
    {
        return 'social';
    }
}
