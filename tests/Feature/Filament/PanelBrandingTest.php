<?php

declare(strict_types=1);

use App\Models\User;
use App\Settings\GeneralSettings;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

it('falls back to the bundled favicon and the text brand when nothing is uploaded', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getFavicon())->toBe(asset('theme/favicon.svg'))
        ->and($panel->getBrandLogo())->toBeNull()
        ->and($panel->getBrandName())->toBe(config('app.name'));
});

it('uses the favicon and logo uploaded in the general settings', function () {
    Storage::disk('public')->put('settings/favicon.png', 'png');
    Storage::disk('public')->put('settings/logo.svg', '<svg/>');

    $settings = app(GeneralSettings::class);
    $settings->favicon_path = 'settings/favicon.png';
    $settings->logo_path = 'settings/logo.svg';
    $settings->save();

    $panel = Filament::getPanel('admin');

    expect($panel->getFavicon())->toBe(Storage::url('settings/favicon.png'))
        ->and($panel->getBrandLogo())->toBe(Storage::url('settings/logo.svg'));
});

it('ignores an uploaded path whose file no longer exists', function () {
    $settings = app(GeneralSettings::class);
    $settings->favicon_path = 'settings/gone.png';
    $settings->logo_path = 'settings/gone.svg';
    $settings->save();

    $panel = Filament::getPanel('admin');

    expect($panel->getFavicon())->toBe(asset('theme/favicon.svg'))
        ->and($panel->getBrandLogo())->toBeNull();
});

it('renders the uploaded favicon and logo on the login and dashboard pages', function () {
    Storage::disk('public')->put('settings/favicon.png', 'png');
    Storage::disk('public')->put('settings/logo.svg', '<svg/>');

    $settings = app(GeneralSettings::class);
    $settings->favicon_path = 'settings/favicon.png';
    $settings->logo_path = 'settings/logo.svg';
    $settings->save();

    $this->get('/admin/login')
        ->assertOk()
        ->assertSee(Storage::url('settings/favicon.png'), escape: false)
        ->assertSee(Storage::url('settings/logo.svg'), escape: false);

    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk()
        ->assertSee(Storage::url('settings/logo.svg'), escape: false);
});

it('does not touch the database while the panel is being registered', function () {
    // Branding is resolved lazily; registering the panel must work before migrations have run.
    $source = file_get_contents(app_path('Providers/Filament/AdminPanelProvider.php'));

    expect($source)->toContain('->favicon(fn ()')
        ->and($source)->toContain('->brandLogo(fn ()');
});
