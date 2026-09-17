<?php

declare(strict_types=1);

use App\Filament\Pages\ManageGeneralSettings;
use App\Filament\Pages\ManageSeoSettings;
use App\Filament\Pages\ManageSocialSettings;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('groups the settings pages under a Turkish navigation group', function (string $page, string $title) {
    expect($page::getNavigationGroup())->toBe('Ayarlar')
        ->and($page::getNavigationLabel())->toBe($title);

    Livewire::test($page)->assertSee($title);
})->with([
    'general' => [ManageGeneralSettings::class, 'Genel'],
    'seo' => [ManageSeoSettings::class, 'SEO'],
    'social' => [ManageSocialSettings::class, 'Sosyal Medya'],
]);

it('renders the general settings form in Turkish', function () {
    Livewire::test(ManageGeneralSettings::class)
        ->assertSee(['Site', 'Yazar', 'Ana Sayfa', 'Alt Bilgi', 'Yasal ve İletişim'])
        ->assertSee(['Site Başlığı', 'Ad Soyad', 'Ünvan', 'E-posta', 'Müsaitlik Durumu', 'İstatistikler', 'KVKK Sayfası'])
        ->assertDontSee(['Site Title', 'Author', 'Homepage Hero', 'Availability Status', 'Footer Description', 'Legal & Contact']);
});

it('renders the seo settings form in Turkish', function () {
    Livewire::test(ManageSeoSettings::class)
        ->assertSee(['Meta Açıklaması', 'Paylaşım Görseli'])
        ->assertDontSee(['Meta Description', 'OG Image Path']);
});

it('stacks the general settings sections in a single balanced column', function () {
    $sections = Livewire::test(ManageGeneralSettings::class)
        ->instance()
        ->form
        ->getComponents();

    expect($sections)->toHaveCount(5);

    foreach ($sections as $section) {
        expect($section->isAside())->toBeTrue()
            ->and($section->getDescription())->not->toBeEmpty();
    }
});

it('does not show the Filament promo widget on the dashboard', function () {
    expect(Filament::getPanel('admin')->getWidgets())
        ->not->toContain(FilamentInfoWidget::class);
});

it('labels the project stats columns in Turkish', function () {
    // The table header only renders once the repeater has a row.
    Livewire::test(CreateProject::class)
        ->fillForm(['stats' => [['label' => 'Uptime', 'value' => '99.97%']]])
        ->assertSee('Metrik')
        ->assertDontSee('Metric');
});
