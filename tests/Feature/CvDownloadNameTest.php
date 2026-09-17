<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('remembers the original file name of the uploaded cv', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([
            'site_title' => 'Site',
            'author_name' => 'Ada Yazar',
            'cv_path' => UploadedFile::fake()->create('Ada Yazar - CV 2026.pdf', 200, 'application/pdf'),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(GeneralSettings::class)->refresh();

    expect($settings->cv_original_name)->toBe('Ada Yazar - CV 2026.pdf')
        ->and($settings->cv_path)->not->toContain('Ada Yazar')
        ->and($settings->cv_path)->toEndWith('.pdf');

    Storage::disk('public')->assertExists($settings->cv_path);
});

it('downloads the cv under the name it was uploaded with', function () {
    Storage::disk('public')->put('cv/01ABC.pdf', 'pdf');

    $settings = app(GeneralSettings::class);
    $settings->author_name = 'Ada Yazar';
    $settings->cv_path = 'cv/01ABC.pdf';
    $settings->cv_original_name = 'Ada Yazar - CV 2026.pdf';
    $settings->save();

    $this->get(route('cv'))
        ->assertSee('href="'.Storage::url('cv/01ABC.pdf').'"', escape: false)
        ->assertSee('download="Ada Yazar - CV 2026.pdf"', escape: false);
});

it('falls back to a name built from the author for cvs uploaded before names were stored', function () {
    Storage::disk('public')->put('cv/01ABC.pdf', 'pdf');

    $settings = app(GeneralSettings::class);
    $settings->author_name = 'Ada Yazar';
    $settings->cv_path = 'cv/01ABC.pdf';
    $settings->cv_original_name = null;
    $settings->save();

    $this->get(route('cv'))->assertSee('download="ada-yazar-cv.pdf"', escape: false);
});
