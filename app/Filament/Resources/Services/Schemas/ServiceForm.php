<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Hizmet Detayları')
                ->description('Hizmetler sayfasında görüntülenen kart için içerik ve fiyatlandırma bilgilerini girin.')
                ->schema([
                    TextInput::make('title')
                        ->label('Başlık')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Textarea::make('description')
                        ->label('Açıklama')
                        ->required()
                        ->rows(4)
                        ->maxLength(2000)
                        ->columnSpanFull(),

                    TagsInput::make('features')
                        ->label('Kapsam (Maddeler)')
                        ->placeholder('Enter ile yeni madde ekle')
                        ->helperText('Her madde ayrı bir tag olarak eklenir. Hizmet kartında liste olarak görünür.')
                        ->columnSpanFull(),
                ]),

            Section::make('Fiyatlandırma & Etiket')
                ->description('Hizmet kartının alt bölümünde gösterilecek bilgiler.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('pricing')
                            ->label('Fiyatlandırma')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Aylık retainer'),

                        TextInput::make('duration')
                            ->label('Süre')
                            ->maxLength(255)
                            ->placeholder('4 hafta')
                            ->helperText('İsteğe bağlı.'),

                        TextInput::make('badge')
                            ->label('Rozet')
                            ->maxLength(255)
                            ->placeholder('Yeni')
                            ->helperText('Boş bırakılırsa kartta "Hizmet" yazar.')
                            ->columnSpanFull(),
                    ]),
                ]),
        ])->columns(1);
    }
}
