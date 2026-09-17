<?php

declare(strict_types=1);

use App\Models\Brand;
use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Post;
use App\Models\Skill;
use App\Models\Tag;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

uses(RefreshDatabase::class);

/**
 * True when $text is the content of an <h2> in $html.
 */
function isSecondLevelHeading(string $html, string $text): bool
{
    return preg_match('#<h2\b[^>]*>\s*'.preg_quote($text, '#').'\s*</h2>#u', $html) === 1;
}

it('renders the eyebrow as a prominent uppercase label', function () {
    $html = Blade::render('<x-eyebrow class="mb-3">Referanslar</x-eyebrow>');

    expect($html)->toContain('Referanslar')
        ->and($html)->toContain('uppercase')
        ->and($html)->toContain('font-semibold')
        ->and($html)->toContain('text-neutral-950')
        ->and($html)->toContain('mb-3')
        ->and($html)->not->toContain('text-neutral-500');
});

it('renders a smaller eyebrow for group titles without the accent line', function () {
    $html = Blade::render('<x-eyebrow size="sm">Kategoriler</x-eyebrow>');

    expect($html)->toContain('text-xs')
        ->and($html)->toContain('text-neutral-950')
        ->and($html)->not->toContain('data-eyebrow-accent');
});

it('renders section headings as real second level headings', function () {
    $html = Blade::render('<x-section-heading>Zaman çizelgesi</x-section-heading>');

    expect(isSecondLevelHeading($html, 'Zaman çizelgesi'))->toBeTrue()
        ->and($html)->toContain('font-semibold')
        ->and($html)->toContain('text-neutral-950');
});

it('uses section headings for the brands strip, the timeline and the process steps', function () {
    Brand::factory()->create();

    expect(isSecondLevelHeading($this->get(route('references'))->getContent(), 'Birlikte çalıştığım markalar'))->toBeTrue()
        ->and(isSecondLevelHeading($this->get(route('about'))->getContent(), 'Zaman çizelgesi'))->toBeTrue()
        ->and(isSecondLevelHeading($this->get(route('services'))->getContent(), 'Nasıl çalışırız'))->toBeTrue();
});

it('uses section headings for every block of the cv', function () {
    $settings = app(GeneralSettings::class);
    $settings->bio = 'Kısa özet.';
    $settings->save();

    Experience::factory()->create();
    Skill::factory()->create();
    Education::factory()->create();
    Language::factory()->create();

    $html = $this->get(route('cv'))->getContent();

    foreach (['Özet', 'Tecrübe', 'Yetkinlikler', 'Eğitim', 'Diller'] as $heading) {
        expect(isSecondLevelHeading($html, $heading))->toBeTrue("CV heading [{$heading}] is not an h2");
    }
});

it('makes the group titles in the footer and the blog sidebar prominent', function () {
    Post::factory()->published()->for(Category::factory())->hasAttached(Tag::factory())->create();

    // The footer contact column is only printed when there is something to contact.
    $settings = app(GeneralSettings::class);
    $settings->author_email = 'ada@example.test';
    $settings->save();

    $blog = $this->get(route('blog'))->getContent();

    foreach (['Kategoriler', 'Etiketler', 'Site', 'Bağlan'] as $title) {
        expect($blog)->toMatch('#<div[^>]*text-neutral-950[^>]*>\s*'.$title.'\s*</div>#u');
    }
});

it('no longer uses the dim grey pattern for eyebrows and section labels in any public view', function () {
    $offenders = collect(File::allFiles(resource_path('views')))
        ->reject(fn ($file) => str_contains($file->getPathname(), '/vendor/'))
        ->filter(fn ($file) => str_contains($file->getContents(), 'text-neutral-500 tracking-[1.4px] uppercase'))
        ->map(fn ($file) => $file->getRelativePathname())
        ->values()
        ->all();

    expect($offenders)->toBe([]);
});

it('puts the footer link columns side by side on small screens instead of stacking them', function () {
    $footer = Str::after($this->get(route('home'))->getContent(), 'role="contentinfo"');

    // Three link columns share a row from the smallest screen up; the brand block spans the row above them.
    expect($footer)->toContain('grid grid-cols-3 lg:grid-cols-[1.5fr_1fr_1fr_1fr]')
        ->toContain('col-span-3 lg:col-span-1')
        ->not->toContain('grid-cols-1 sm:grid-cols-2');
});
