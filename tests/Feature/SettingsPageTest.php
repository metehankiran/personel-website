<?php

use App\Filament\Pages\ManageGeneralSettings;
use App\Filament\Pages\ManageSeoSettings;
use App\Filament\Pages\ManageSocialSettings;
use App\Models\User;
use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('general settings page can be rendered', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->assertOk();
});

test('general settings can be updated', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([
            'site_title' => 'Updated Title',
            'author_name' => 'Test Author',
            'author_email' => 'test@example.com',
        ])
        ->call('save')
        ->assertNotified();

    $settings = app(GeneralSettings::class);
    expect($settings->site_title)->toBe('Updated Title');
    expect($settings->author_name)->toBe('Test Author');
    expect($settings->author_email)->toBe('test@example.com');
});

test('seo settings page can be rendered', function () {
    Livewire::test(ManageSeoSettings::class)
        ->assertOk();
});

test('seo settings can be updated', function () {
    Livewire::test(ManageSeoSettings::class)
        ->fillForm([
            'meta_description' => 'A test description',
            'google_analytics_id' => 'G-TESTID123',
        ])
        ->call('save')
        ->assertNotified();

    $settings = app(SeoSettings::class);
    expect($settings->meta_description)->toBe('A test description');
    expect($settings->google_analytics_id)->toBe('G-TESTID123');
});

test('social settings page can be rendered', function () {
    Livewire::test(ManageSocialSettings::class)
        ->assertOk();
});

test('social settings can be updated', function () {
    Livewire::test(ManageSocialSettings::class)
        ->fillForm([
            'github_url' => 'https://github.com/testuser',
            'linkedin_url' => 'https://linkedin.com/in/testuser',
        ])
        ->call('save')
        ->assertNotified();

    $settings = app(SocialSettings::class);
    expect($settings->github_url)->toBe('https://github.com/testuser');
    expect($settings->linkedin_url)->toBe('https://linkedin.com/in/testuser');
});

test('general settings validates required fields', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([
            'site_title' => '',
            'author_name' => '',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'site_title' => 'required',
            'author_name' => 'required',
        ]);
});
