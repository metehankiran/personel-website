<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Marka Bilgileri')
                ->description('Referanslar sayfasında görüntülenen marka kartını yapılandırın.')
                ->schema([
                    FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('brands')
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['3:1', '16:9', '4:3', '1:1'])
                        ->imageEditorMode(2)
                        ->helperText('Önerilen oran 3:1 (örn. 600×200 px). Yükledikten sonra kalem ikonuna tıklayıp hazır oran düğmelerinden kırpabilirsin.')
                        ->columnSpanFull(),
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Marka Adı')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('Web Sitesi')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com')
                            ->helperText('İsteğe bağlı. Boş bırakılırsa marka kartı tıklanamaz.'),
                    ]),
                ]),
        ])->columns(1);
    }
}
