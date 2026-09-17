<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

function uploadLogos(?string $light, ?string $dark): void
{
    $settings = app(GeneralSettings::class);

    foreach (['logo_path' => $light, 'logo_dark_path' => $dark] as $field => $path) {
        if ($path !== null) {
            Storage::disk('public')->put($path, '<svg/>');
        }

        $settings->{$field} = $path;
    }

    $settings->save();
}

function headerOf(string $html): string
{
    return Str::between($html, '<header', '</header>');
}

it('draws the ring mark when no logo is uploaded', function () {
    $header = headerOf($this->get(route('home'))->getContent());

    expect($header)->toContain('data-brand-ring')
        ->toContain('fill-rule="evenodd"')
        ->not->toContain('data-site-logo');
});

it('draws the same ring everywhere instead of four hand made variants', function () {
    $post = Post::factory()->published()->for(Category::factory())->create();

    foreach ([route('home'), route('blog.show', $post)] as $url) {
        $html = $this->get($url)->getContent();

        expect($html)->not->toContain('rounded-full bg-neutral-950 dark:bg-neutral-50 relative');
    }

    // header + footer + the profile card on the home page
    expect(substr_count($this->get(route('home'))->getContent(), 'data-brand-ring'))->toBe(3);
});

it('shows one logo in both themes when only the light logo is uploaded', function () {
    uploadLogos('settings/logo.svg', null);

    $header = headerOf($this->get(route('home'))->getContent());

    expect(substr_count($header, 'data-site-logo'))->toBe(1)
        ->and($header)->toContain('src="'.Storage::url('settings/logo.svg').'"')
        ->and($header)->not->toContain('dark:hidden')
        ->and($header)->not->toContain('data-brand-ring');
});

it('swaps the logo with the theme when both logos are uploaded', function () {
    uploadLogos('settings/logo.svg', 'settings/logo-dark.svg');

    $header = headerOf($this->get(route('home'))->getContent());

    expect($header)->toMatch('#<img data-site-logo="light"[^>]*src="'.preg_quote(Storage::url('settings/logo.svg'), '#').'"[^>]*class="[^"]*dark:hidden#')
        ->toMatch('#<img data-site-logo="dark"[^>]*src="'.preg_quote(Storage::url('settings/logo-dark.svg'), '#').'"[^>]*class="[^"]*hidden dark:block#');
});

it('falls back to the dark logo in both themes when it is the only one uploaded', function () {
    uploadLogos(null, 'settings/logo-dark.svg');

    $header = headerOf($this->get(route('home'))->getContent());

    expect(substr_count($header, 'data-site-logo'))->toBe(1)
        ->and($header)->toContain('src="'.Storage::url('settings/logo-dark.svg').'"');
});

it('uses the uploaded logos in the footer too, but keeps the ring as the avatar on the home card', function () {
    uploadLogos('settings/logo.svg', 'settings/logo-dark.svg');

    $html = $this->get(route('home'))->getContent();
    $footer = Str::after($html, 'role="contentinfo"');

    expect($footer)->toContain('data-site-logo="light"')->toContain('data-site-logo="dark"')
        ->and(substr_count($html, 'data-brand-ring'))->toBe(1);
});

it('ignores a logo path whose file is gone', function () {
    $settings = app(GeneralSettings::class);
    $settings->logo_path = 'settings/gone.svg';
    $settings->logo_dark_path = 'settings/gone-dark.svg';
    $settings->save();

    expect(headerOf($this->get(route('home'))->getContent()))->toContain('data-brand-ring')->not->toContain('data-site-logo');
});

it('offers a dark theme logo upload next to the logo in the general settings', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManageGeneralSettings::class)
        ->assertSee(['Logo (açık tema)', 'Logo (koyu tema)'])
        ->assertFormFieldExists('logo_dark_path', fn ($field): bool => $field instanceof FileUpload && $field->getDiskName() === 'public');
});

it('stores the dark logo and removes the old file when it is replaced', function () {
    $this->actingAs(User::factory()->create());
    uploadLogos(null, 'settings/old-dark.svg');

    Livewire::test(ManageGeneralSettings::class)
        ->set('data.logo_dark_path', [])
        ->fillForm(['site_title' => 'Site', 'author_name' => 'Author', 'logo_dark_path' => UploadedFile::fake()->image('dark.png', 400, 120)])
        ->call('save')
        ->assertHasNoFormErrors();

    $path = app(GeneralSettings::class)->refresh()->logo_dark_path;

    expect($path)->not->toBe('settings/old-dark.svg');
    Storage::disk('public')->assertExists($path);
    Storage::disk('public')->assertMissing('settings/old-dark.svg');
});

it('gives the admin panel the matching logo for each of its themes', function () {
    uploadLogos('settings/logo.svg', 'settings/logo-dark.svg');

    $panel = Filament::getPanel('admin');

    expect($panel->getBrandLogo())->toBe(Storage::url('settings/logo.svg'))
        ->and($panel->getDarkModeBrandLogo())->toBe(Storage::url('settings/logo-dark.svg'));
});

it('keeps the text brand in the panel when no logo is uploaded', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getBrandLogo())->toBeNull()->and($panel->getDarkModeBrandLogo())->toBeNull();
});
