<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\DeletesReplacedUploads;
use App\Settings\SeoSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSeoSettings extends SettingsPage
{
    use DeletesReplacedUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string $settings = SeoSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?string $title = 'SEO';

    protected static ?int $navigationSort = 3;

    /**
     * @return array<int, string>
     */
    protected function uploadFields(): array
    {
        return ['og_image_path'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Arama ve Paylaşım')
                    ->description('Arama sonuçlarında ve sosyal medyada paylaşıldığında sitenin nasıl görüneceği.')
                    ->aside()
                    ->schema([
                        Textarea::make('meta_description')
                            ->label('Meta Açıklaması')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('En fazla 160 karakter. Sayfanın kendi açıklaması yoksa bu metin kullanılır.'),
                        FileUpload::make('og_image_path')
                            ->label('Paylaşım Görseli')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->imageEditorAspectRatioOptions(['1.91:1'])
                            ->imageEditorMode(2)
                            ->maxSize(2048)
                            ->helperText('Önerilen boyut 1200×630 px (PNG veya JPG). Boş bırakılırsa varsayılan görsel kullanılır.'),
                    ]),
                Section::make('Google')
                    ->description('Ziyaretçi ölçümü ve Search Console site doğrulaması.')
                    ->aside()
                    ->schema([
                        TextInput::make('google_analytics_id')
                            ->label('Google Analytics Kimliği')
                            ->placeholder('G-XXXXXXXXXX')
                            ->maxLength(50),
                        TextInput::make('google_search_console_id')
                            ->label('Search Console Doğrulama Kodu')
                            ->maxLength(100),
                    ]),
            ]);
    }
}
