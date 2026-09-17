<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\DeletesReplacedUploads;
use App\Models\Page;
use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;
use UnitEnum;

class ManageGeneralSettings extends SettingsPage
{
    use DeletesReplacedUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = GeneralSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?string $title = 'Genel';

    protected static ?int $navigationSort = 1;

    /**
     * @return array<int, string>
     */
    protected function uploadFields(): array
    {
        return ['logo_path', 'favicon_path', 'cv_path'];
    }

    /**
     * Static pages keyed by slug, because the site links to them by slug.
     *
     * @return array<string, string>
     */
    protected function staticPageOptions(): array
    {
        // Sort in PHP: database collations disagree on Turkish letters (SQLite orders bytewise).
        return Page::query()
            ->get(['title', 'slug', 'is_published'])
            ->sortBy(fn (Page $page): string => Str::ascii(Str::lower($page->title)))
            ->mapWithKeys(fn (Page $page): array => [
                $page->slug => $page->is_published ? $page->title : "{$page->title} (taslak)",
            ])
            ->all();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Site')
                    ->description('Tarayıcı sekmesinde ve arama sonuçlarında görünen temel site bilgileri.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_title')->label('Site Başlığı')->required()->maxLength(255)->columnSpanFull(),
                        TextInput::make('site_description')->label('Site Açıklaması')->maxLength(500)->columnSpanFull(),
                        FileUpload::make('logo_path')
                            ->label('Logo')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings')
                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp', 'image/jpeg'])
                            ->maxSize(1024)
                            ->imagePreviewHeight('120')
                            ->helperText('SVG veya şeffaf PNG önerilir. Üst menüde ve alt bilgide görünür; boşsa varsayılan işaret kullanılır.'),
                        FileUpload::make('favicon_path')
                            ->label('Favicon')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings')
                            ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/x-icon', 'image/vnd.microsoft.icon'])
                            ->maxSize(512)
                            ->imagePreviewHeight('120')
                            ->helperText('SVG, PNG veya ICO. Kare olmalı (örn. 64×64 px). Boşsa varsayılan favicon kullanılır.'),
                    ]),
                Section::make('Yazar')
                    ->description('Hakkımda, CV ve iletişim sayfalarında gösterilen kişisel bilgiler.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('author_name')->label('Ad Soyad')->required()->maxLength(255),
                        TextInput::make('author_title')->label('Ünvan')->maxLength(255)->placeholder('Full-stack Developer'),
                        TextInput::make('author_email')->label('E-posta')->email()->maxLength(255)
                            ->helperText('İletişim formu bildirimleri bu adrese gönderilir.'),
                        TextInput::make('author_phone')->label('Telefon')->tel()->maxLength(50),
                        TextInput::make('author_location')->label('Konum')->maxLength(100)->placeholder('İstanbul, TR'),
                        TextInput::make('author_address')->label('Adres')->maxLength(500),
                        Textarea::make('bio')->label('Biyografi / Özet')->rows(4)->columnSpanFull(),
                        Hidden::make('cv_original_name'),
                        FileUpload::make('cv_path')
                            ->label('CV (PDF)')
                            ->disk('public')
                            ->visibility('public')
                            ->directory('cv')
                            ->storeFileNamesIn('cv_original_name')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(5120)
                            ->downloadable()
                            ->openable()
                            ->helperText('CV sayfasındaki "İndir" düğmesi ve site araması bu dosyaya bağlanır. Ziyaretçi dosyayı senin yüklediğin adla indirir. En fazla 5 MB.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Ana Sayfa')
                    ->description('Ana sayfanın en üstündeki karşılama alanı ve istatistik şeridi.')
                    ->aside()
                    ->schema([
                        TextInput::make('availability_status')->label('Müsaitlik Durumu')->maxLength(255)->placeholder('Yeni proje alıyor'),
                        RichEditor::make('hero_title')
                            ->label('Karşılama Başlığı')
                            ->toolbarButtons([['bold', 'italic', 'underline'], ['undo', 'redo']])
                            ->helperText('İtalik yapılan kelimeler sitede ince italik vurgu olarak görünür. Enter ile alt satıra geçebilirsin.'),
                        Textarea::make('hero_subtitle')->label('Karşılama Alt Başlığı')->rows(3),
                        Repeater::make('homepage_stats')
                            ->label('İstatistikler')
                            ->table([
                                TableColumn::make('Etiket')->markAsRequired(),
                                TableColumn::make('Değer')->markAsRequired(),
                            ])
                            ->schema([
                                TextInput::make('label')->label('Etiket')->required()->placeholder('Tecrübe'),
                                TextInput::make('value')->label('Değer')->required()->placeholder('5+ yıl'),
                            ])
                            ->addActionLabel('İstatistik ekle')
                            ->defaultItems(0),
                    ]),
                Section::make('Alt Bilgi')
                    ->description('Her sayfanın altındaki footer alanında görünen tanıtım metni.')
                    ->aside()
                    ->schema([
                        Textarea::make('footer_text')->label('Alt Bilgi Metni')->rows(3),
                    ]),
                Section::make('Yasal ve İletişim')
                    ->description('Çerez bildirimi ve KVKK onayının bağlandığı statik sayfalar ile iletişim sayfasındaki harita bağlantısı.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        Select::make('kvkk_page_slug')
                            ->label('KVKK Sayfası')
                            ->options(fn (): array => $this->staticPageOptions())
                            ->searchable()
                            ->placeholder('Sayfa seç')
                            ->helperText('Seçilirse iletişim formunda KVKK onay kutusu çıkar.'),
                        Select::make('cookie_policy_slug')
                            ->label('Çerez Politikası Sayfası')
                            ->options(fn (): array => $this->staticPageOptions())
                            ->searchable()
                            ->placeholder('Sayfa seç')
                            ->helperText('Çerez bildirimindeki bağlantı bu sayfaya gider.'),
                        TextInput::make('google_maps_url')->label('Google Haritalar Bağlantısı')->url()->maxLength(500)->columnSpanFull(),
                    ]),
            ]);
    }
}
