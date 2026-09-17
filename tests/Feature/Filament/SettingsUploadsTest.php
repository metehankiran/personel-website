<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Filament\Pages\ManageSeoSettings;
use App\Models\User;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

/**
 * @return array<string, string>
 */
function requiredGeneralFields(): array
{
    return ['site_title' => 'Site', 'author_name' => 'Author'];
}

beforeEach(function () {
    Storage::fake('public');

    $this->actingAs(User::factory()->create());
});

it('uses file uploads instead of path inputs on the general settings page', function (string $field) {
    Livewire::test(ManageGeneralSettings::class)
        ->assertFormFieldExists($field, fn ($component): bool => $component instanceof FileUpload
            && $component->getDiskName() === 'public'
            && $component->getVisibility() === 'public');
})->with(['logo_path', 'favicon_path', 'cv_path']);

it('uses a file upload for the share image on the seo settings page', function () {
    Livewire::test(ManageSeoSettings::class)
        ->assertFormFieldExists('og_image_path', fn ($component): bool => $component instanceof FileUpload
            && $component->getDiskName() === 'public');
});

it('stores uploaded logo, favicon and cv files on the public disk', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([
            ...requiredGeneralFields(),
            'logo_path' => UploadedFile::fake()->image('logo.png', 400, 120),
            'favicon_path' => UploadedFile::fake()->image('favicon.png', 64, 64),
            'cv_path' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf'),
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(GeneralSettings::class)->refresh();

    foreach (['logo_path', 'favicon_path', 'cv_path'] as $field) {
        expect($settings->{$field})->not->toBeEmpty();
        Storage::disk('public')->assertExists($settings->{$field});
    }
});

it('rejects a cv that is not a pdf', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([...requiredGeneralFields(), 'cv_path' => UploadedFile::fake()->image('cv.png')])
        ->call('save')
        ->assertHasFormErrors(['cv_path']);
});

it('stores the uploaded share image on the public disk', function () {
    Livewire::test(ManageSeoSettings::class)
        ->fillForm(['og_image_path' => UploadedFile::fake()->image('og.png', 1200, 630)])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk('public')->assertExists(app(SeoSettings::class)->refresh()->og_image_path);
});

it('deletes the previous file when an upload is replaced', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([...requiredGeneralFields(), 'logo_path' => UploadedFile::fake()->image('old.png')])
        ->call('save');

    $oldPath = app(GeneralSettings::class)->refresh()->logo_path;

    // The browser removes the current file before uploading its replacement.
    Livewire::test(ManageGeneralSettings::class)
        ->set('data.logo_path', [])
        ->fillForm([...requiredGeneralFields(), 'logo_path' => UploadedFile::fake()->image('new.png')])
        ->call('save');

    $newPath = app(GeneralSettings::class)->refresh()->logo_path;

    expect($newPath)->not->toBe($oldPath);
    Storage::disk('public')->assertMissing($oldPath);
    Storage::disk('public')->assertExists($newPath);
});

it('deletes the file when an upload is cleared', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([...requiredGeneralFields(), 'cv_path' => UploadedFile::fake()->create('cv.pdf', 200, 'application/pdf')])
        ->call('save');

    $path = app(GeneralSettings::class)->refresh()->cv_path;
    Storage::disk('public')->assertExists($path);

    Livewire::test(ManageGeneralSettings::class)
        ->fillForm(requiredGeneralFields())
        ->set('data.cv_path', [])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(GeneralSettings::class)->refresh()->cv_path)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});

it('renders the uploaded favicon and logo on the public site', function () {
    Storage::disk('public')->put('settings/favicon.png', 'png');
    Storage::disk('public')->put('settings/logo.svg', '<svg/>');

    $settings = app(GeneralSettings::class);
    $settings->favicon_path = 'settings/favicon.png';
    $settings->logo_path = 'settings/logo.svg';
    $settings->save();

    $this->get(route('home'))
        ->assertSee('<link rel="icon" href="'.Storage::url('settings/favicon.png').'"', escape: false)
        ->assertSee('src="'.Storage::url('settings/logo.svg').'"', escape: false);
});

it('falls back to the bundled favicon and brand mark when nothing is uploaded', function () {
    $this->get(route('home'))
        ->assertSee('<link rel="icon" href="'.asset('theme/favicon.svg').'"', escape: false)
        ->assertDontSee('data-site-logo', escape: false);
});
