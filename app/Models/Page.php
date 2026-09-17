<?php

namespace App\Models;

use App\Models\Concerns\HasPublishingState;
use App\Settings\GeneralSettings;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory;

    use HasPublishingState;

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'show_footer' => 'boolean',
        ];
    }

    /**
     * Settings that point at a static page by its slug.
     *
     * @var array<int, string>
     */
    private const array LINKED_SETTINGS = ['kvkk_page_slug', 'cookie_policy_slug'];

    protected static function booted(): void
    {
        static::updated(function (Page $page) {
            if ($page->wasChanged('slug')) {
                static::repointLinkedSettings($page->getOriginal('slug'), $page->slug);
            }
        });

        static::deleted(function (Page $page) {
            static::repointLinkedSettings($page->slug, null);
        });
    }

    /**
     * URL of a static page, or null when it does not exist or is not published.
     */
    public static function publicUrl(?string $slug): ?string
    {
        if (blank($slug) || ! static::published()->where('slug', $slug)->exists()) {
            return null;
        }

        return route('pages.show', $slug);
    }

    /**
     * Settings store page slugs as plain strings, so the database cannot keep
     * them in sync; follow renames and clear references to deleted pages here.
     */
    private static function repointLinkedSettings(string $from, ?string $to): void
    {
        $settings = app(GeneralSettings::class);

        foreach (self::LINKED_SETTINGS as $setting) {
            if ($settings->{$setting} === $from) {
                $settings->{$setting} = $to;
            }
        }

        $settings->save();
    }
}
