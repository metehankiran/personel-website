# personel-website

Source of [metehankiran.com](https://metehankiran.com), my personal site: blog, projects, services, CV, references, bookmarks, FAQ and a contact form.

The public side is server-rendered Blade with a little Alpine. All content, including site settings and SEO defaults, is managed from a Filament admin panel. The site is in Turkish, so the URLs are too (`/hakkimda`, `/projeler`); the code is in English.

## Stack

PHP 8.5, Laravel 13, Filament 5, Livewire 4, Tailwind v4, Vite, Pest 4.

Production runs on MySQL 8. The test suite runs on in-memory SQLite, so migrations and queries have to work on both.

## Setup

You need PHP 8.3+, Composer and Node.

    git clone https://github.com/metehankiran/personel-website.git
    cd personel-website
    composer setup

`composer setup` installs dependencies, creates `.env`, generates the app key, runs migrations and builds the frontend. The default `.env` uses SQLite. To use MySQL, set the `DB_*` values in `.env` and run `php artisan migrate` again.

To fill the site with sample content and create an admin user:

    php artisan db:seed

This creates `root@personel-website.test` with the password `password`. Change it before putting the site anywhere public.

## Development

    composer run dev

Starts the PHP server, queue worker, log tail and Vite in one terminal.

| Task | Command |
| --- | --- |
| Run all tests | `composer test` |
| Run one test file | `php artisan test --compact tests/Feature/SomeTest.php` |
| Format changed PHP files | `vendor/bin/pint --dirty --format agent` |

Work is test-first: write a failing test, make it pass, run Pint, commit. Commits follow [Conventional Commits](https://www.conventionalcommits.org).

## How it is organised

- `routes/web.php` holds every public route. Views link with `route('name')`, so a URL can change without touching a template.
- `app/Http/Controllers`: `PageController` serves the static pages. Blog, projects, bookmarks and the rest have small controllers with only the actions they need.
- `app/Filament`: the admin panel at `/admin`. One resource per content type, plus settings pages for general info, about, social links and SEO.
- `app/Settings`: site-wide settings stored in the database (`spatie/laravel-settings`).
- `resources/views`: one layout (`layouts/app.blade.php`), reusable `components/`, and a file per page under `pages/`.
- `SeoController` generates `sitemap.xml`, `robots.txt`, `llms.txt` and the favicon. Pages output their own JSON-LD (Person, BlogPosting, Service, FAQPage, breadcrumbs).
- `/search/index` returns the JSON that the command palette searches on the client.

## Deployment

`scripts/deploy.sh` runs on the server. It puts the site in maintenance mode, installs dependencies, builds assets, runs migrations, rebuilds the caches and brings the site back up, even if a step fails.

## Roadmap

- English version under an `/en` prefix
- Scheduled backups to S3
- RSS feed
- AVIF/WebP image pipeline
- Full-text search with Meilisearch
- Double opt-in for the newsletter
- Lighthouse CI on pull requests, auto-deploy from `main`

## License

MIT
