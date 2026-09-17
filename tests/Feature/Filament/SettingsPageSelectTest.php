<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Models\Page;
use App\Models\User;
use App\Settings\GeneralSettings;
use Filament\Forms\Components\Select;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('picks the legal pages from existing static pages', function (string $field) {
    Page::factory()->published()->create(['title' => 'KVKK Aydınlatma Metni', 'slug' => 'kvkk']);
    Page::factory()->create(['title' => 'Çerez Politikası', 'slug' => 'cookie-policy']);

    Livewire::test(ManageGeneralSettings::class)
        ->assertFormFieldExists($field, fn ($component): bool => $component instanceof Select
            && $component->getOptions() === [
                'cookie-policy' => 'Çerez Politikası (taslak)',
                'kvkk' => 'KVKK Aydınlatma Metni',
            ]);
})->with(['kvkk_page_slug', 'cookie_policy_slug']);

it('stores the slug of the selected page', function () {
    Page::factory()->published()->create(['title' => 'KVKK', 'slug' => 'kvkk']);
    Page::factory()->published()->create(['title' => 'Çerezler', 'slug' => 'cookie-policy']);

    Livewire::test(ManageGeneralSettings::class)
        ->fillForm([
            'site_title' => 'Site',
            'author_name' => 'Author',
            'kvkk_page_slug' => 'kvkk',
            'cookie_policy_slug' => 'cookie-policy',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(GeneralSettings::class)->refresh();

    expect($settings->kvkk_page_slug)->toBe('kvkk')
        ->and($settings->cookie_policy_slug)->toBe('cookie-policy');
});

it('rejects a slug that does not belong to an existing page', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->fillForm(['site_title' => 'Site', 'author_name' => 'Author', 'kvkk_page_slug' => 'missing-page'])
        ->call('save')
        ->assertHasFormErrors(['kvkk_page_slug']);
});

it('allows clearing the selected page', function () {
    $settings = app(GeneralSettings::class);
    $settings->kvkk_page_slug = 'kvkk';
    $settings->save();

    Page::factory()->published()->create(['slug' => 'kvkk']);

    Livewire::test(ManageGeneralSettings::class)
        ->fillForm(['site_title' => 'Site', 'author_name' => 'Author', 'kvkk_page_slug' => null])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(GeneralSettings::class)->refresh()->kvkk_page_slug)->toBeNull();
});
