<?php

declare(strict_types=1);

use App\Enums\LanguageLevel;
use App\Filament\Pages\ManageAboutSettings;
use App\Models\Language;
use App\Models\User;
use App\Settings\AboutSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

function aboutSettings(array $values): void
{
    $settings = app(AboutSettings::class);

    foreach ($values as $key => $value) {
        $settings->{$key} = $value;
    }

    $settings->save();
}

it('ships the previously hardcoded about copy as defaults', function () {
    $settings = app(AboutSettings::class);

    expect($settings->heading)->toBe('5 yıldır kod yazıyor, ürün teslim ediyorum.')
        ->and($settings->body)->toContain('<em>fark edilmeyen</em>')
        ->and($settings->work_mode)->toBe('Uzaktan')
        ->and($settings->portrait_path)->toBeNull();
});

it('renders the heading and body from the settings', function () {
    aboutSettings([
        'heading' => 'On yıldır ürün geliştiriyorum.',
        'body' => '<p>İlk paragraf.</p><p>İkinci <strong>paragraf</strong>.</p>',
    ]);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('On yıldır ürün geliştiriyorum.')
        ->assertSee('<p>İkinci <strong>paragraf</strong>.</p>', escape: false)
        ->assertDontSee('5 yıldır kod yazıyor');
});

it('hides the heading and body blocks when they are empty', function () {
    aboutSettings(['heading' => null, 'body' => null]);

    $this->get(route('about'))->assertOk()->assertDontSee('data-about-body', escape: false);
});

it('shows the uploaded portrait and no placeholder text', function () {
    Storage::disk('public')->put('settings/portrait.jpg', 'jpg');
    aboutSettings(['portrait_path' => 'settings/portrait.jpg']);

    $this->get(route('about'))
        ->assertSee('src="'.Storage::url('settings/portrait.jpg').'"', escape: false)
        ->assertDontSee('portre fotoğrafı');
});

it('leaves the portrait box out when nothing is uploaded', function () {
    $this->get(route('about'))
        ->assertDontSee('portre fotoğrafı')
        ->assertDontSee('data-about-portrait', escape: false);
});

it('takes the work mode from the settings and hides the row when empty', function () {
    aboutSettings(['work_mode' => 'Hibrit · Kütahya']);
    $this->get(route('about'))->assertSee('Hibrit · Kütahya');

    aboutSettings(['work_mode' => null]);
    $this->get(route('about'))->assertDontSee('Çalışma şekli');
});

it('lists the languages managed in the panel instead of a fixed text', function () {
    Language::factory()->create(['name' => 'Türkçe', 'level' => LanguageLevel::Native, 'sort_order' => 1]);
    Language::factory()->create(['name' => 'Almanca', 'level' => LanguageLevel::Intermediate, 'sort_order' => 2]);

    $this->get(route('about'))
        ->assertSee('Türkçe · Almanca')
        ->assertDontSee('TR · EN');
});

it('hides the languages row when there are none', function () {
    $this->get(route('about'))->assertDontSee('TR · EN')->assertDontSee('>Diller<', escape: false);
});

it('offers an about settings page in Turkish under the settings group', function () {
    $this->actingAs(User::factory()->create());

    expect(ManageAboutSettings::getNavigationGroup())->toBe('Ayarlar')
        ->and(ManageAboutSettings::getNavigationLabel())->toBe('Hakkımda');

    Livewire::test(ManageAboutSettings::class)
        ->assertOk()
        ->assertSee(['Tanıtım', 'Başlık', 'Portre', 'Çalışma Şekli'])
        ->assertFormFieldExists('body', fn ($field): bool => $field instanceof RichEditor)
        ->assertFormFieldExists('portrait_path', fn ($field): bool => $field instanceof FileUpload && $field->getDiskName() === 'public');
});

it('saves the about settings and stores the portrait', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ManageAboutSettings::class)
        ->fillForm([
            'heading' => 'Yeni başlık',
            'body' => '<p>Yeni metin.</p>',
            'work_mode' => 'Uzaktan',
            'portrait_path' => UploadedFile::fake()->image('portre.jpg', 800, 1000),
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $settings = app(AboutSettings::class)->refresh();

    expect($settings->heading)->toBe('Yeni başlık')
        ->and($settings->body)->toContain('Yeni metin.');

    Storage::disk('public')->assertExists($settings->portrait_path);
});

it('removes the old portrait file when it is replaced', function () {
    $this->actingAs(User::factory()->create());
    Storage::disk('public')->put('settings/old.jpg', 'jpg');
    aboutSettings(['portrait_path' => 'settings/old.jpg']);

    Livewire::test(ManageAboutSettings::class)
        ->set('data.portrait_path', [])
        ->fillForm(['portrait_path' => UploadedFile::fake()->image('new.jpg')])
        ->call('save')
        ->assertHasNoFormErrors();

    Storage::disk('public')->assertMissing('settings/old.jpg');
});
