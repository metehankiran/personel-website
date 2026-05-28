# personel-website

A personal site built on Laravel — blog, projects, services, CV, references, bookmarks, and a contact form. Content is managed through a Filament admin panel; the public side is plain server-rendered Blade backed by SQLite.

## Stack

PHP 8.5, Laravel 13, Filament 5, Tailwind v4, Pest 4.

## Run

    composer setup        # install, key, migrate, build
    composer run dev      # serve + queue + log + vite
    composer test
    vendor/bin/pint --dirty --format agent

Admin lives at `/admin`. Site-wide settings (author info, SEO, cookie policy) are edited there.

## Roadmap

- Multilang (TR/EN) via `spatie/laravel-translatable` and an `/en` URL prefix
- Scheduled backups with `spatie/laravel-backup` to S3
- RSS feed, automated sitemap, image AVIF/WebP pipeline
- Full-text search with Meilisearch
- Newsletter v2 (Resend or Postmark, double opt-in)
- Lighthouse CI on PR, auto-deploy from `main`

## License

MIT.
