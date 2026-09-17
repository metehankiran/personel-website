<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Soru ve Yanıt')
                ->description('Sitedeki "Sıkça Sorulan Sorular" sayfasında (/sss) görünür. Arama motorları ve yapay zekâ asistanları doğrudan bu yanıtları alıntılar.')
                ->schema([
                    TextInput::make('question')
                        ->label('Soru')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Bir proje ne kadar sürer?')
                        ->helperText('Ziyaretçinin arama kutusuna yazacağı gibi, soru cümlesi olarak yaz.'),

                    Textarea::make('answer')
                        ->label('Yanıt')
                        ->required()
                        ->rows(5)
                        ->maxLength(1500)
                        ->helperText('İlk cümlede doğrudan yanıt ver; ayrıntıyı sonra ekle. 40-60 kelime öne çıkan yanıtlar için idealdir.'),

                    Toggle::make('is_published')
                        ->label('Yayında')
                        ->default(true),
                ]),
        ])->columns(1);
    }
}
