<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\DeletesReplacedUploads;
use App\Settings\AboutSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageAboutSettings extends SettingsPage
{
    use DeletesReplacedUploads;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static string $settings = AboutSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?string $title = 'Hakkımda';

    protected static ?int $navigationSort = 2;

    /**
     * @return array<int, string>
     */
    protected function uploadFields(): array
    {
        return ['portrait_path'];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Tanıtım')
                    ->description('Hakkımda sayfasının üstündeki büyük başlık ve altındaki tanıtım metni.')
                    ->aside()
                    ->schema([
                        TextInput::make('heading')
                            ->label('Başlık')
                            ->maxLength(255)
                            ->placeholder('5 yıldır kod yazıyor, ürün teslim ediyorum.'),
                        RichEditor::make('body')
                            ->label('Tanıtım Metni')
                            ->toolbarButtons([['bold', 'italic', 'underline', 'link'], ['bulletList', 'orderedList'], ['undo', 'redo']])
                            ->helperText('Her paragraf ayrı bir blok olarak görünür. Süre ve müşteri sayısı gibi bilgileri güncel tutmayı unutma.'),
                    ]),
                Section::make('Portre')
                    ->description('Sayfanın sağ sütununda görünen fotoğraf. Yüklenmezse bu alan sitede hiç gösterilmez.')
                    ->aside()
                    ->schema([
                        FileUpload::make('portrait_path')
                            ->label('Portre Fotoğrafı')
                            ->image()
                            ->disk('public')
                            ->visibility('public')
                            ->directory('settings')
                            ->imageEditor()
                            ->imageEditorAspectRatioOptions(['4:5', '1:1'])
                            ->imageEditorMode(2)
                            ->maxSize(2048)
                            ->helperText('Önerilen oran 4:5 (dikey), örn. 800×1000 px. En fazla 2 MB.'),
                    ]),
                Section::make('Hızlı Bilgiler')
                    ->description('Sağ sütundaki bilgi kutusu. Lokasyon Genel ayarlardan, diller Hakkımda → Diller listesinden gelir.')
                    ->aside()
                    ->schema([
                        TextInput::make('work_mode')
                            ->label('Çalışma Şekli')
                            ->maxLength(100)
                            ->placeholder('Uzaktan')
                            ->helperText('Boş bırakılırsa bu satır sitede gösterilmez.'),
                    ]),
            ]);
    }
}
