<?php

declare(strict_types=1);

use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

function configureCv(?string $originalName = 'Ada Yazar - CV 2026.pdf'): void
{
    Storage::disk('public')->put('cv/01ABC.pdf', 'pdf');

    $settings = app(GeneralSettings::class);
    $settings->author_name = 'Ada Yazar';
    $settings->cv_path = 'cv/01ABC.pdf';
    $settings->cv_original_name = $originalName;
    $settings->save();
}

it('shows a view button on the left and a download button on the right', function () {
    configureCv();

    $this->get(route('about'))
        ->assertOk()
        ->assertSeeInOrder(['data-cv-view', 'CV görüntüle', 'data-cv-download', 'CV indir'], escape: false);
});

it('links the view button to the cv page', function () {
    configureCv();

    $this->get(route('about'))
        ->assertSee('href="'.route('cv').'" data-cv-view', escape: false);
});

it('downloads the file exactly like the cv page does', function () {
    configureCv();

    $expected = 'href="'.Storage::url('cv/01ABC.pdf').'" data-cv-download download="Ada Yazar - CV 2026.pdf"';

    $this->get(route('about'))->assertSee($expected, escape: false);

    // Same URL and download name on the cv page itself.
    $this->get(route('cv'))
        ->assertSee('href="'.Storage::url('cv/01ABC.pdf').'"', escape: false)
        ->assertSee('download="Ada Yazar - CV 2026.pdf"', escape: false);
});

it('uses the author based fallback name on both pages when no original name is stored', function () {
    configureCv(originalName: null);

    $this->get(route('about'))->assertSee('download="ada-yazar-cv.pdf"', escape: false);
    $this->get(route('cv'))->assertSee('download="ada-yazar-cv.pdf"', escape: false);
});

it('only offers the view button when no cv file is uploaded', function () {
    $this->get(route('about'))
        ->assertSee('CV görüntüle')
        ->assertDontSee('data-cv-download', escape: false)
        ->assertDontSee('CV indir');
});

it('no longer labels a plain link to the cv page as a download', function () {
    $this->get(route('about'))->assertDontSee("CV'yi indir", escape: false);
});

it('exposes the cv url and download name on the settings', function () {
    configureCv();

    $settings = app(GeneralSettings::class);

    expect($settings->cvUrl())->toBe(Storage::url('cv/01ABC.pdf'))
        ->and($settings->cvDownloadName())->toBe('Ada Yazar - CV 2026.pdf');

    $settings->cv_path = null;

    expect($settings->cvUrl())->toBeNull();
});
