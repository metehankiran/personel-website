<?php

namespace App\Filament\Resources\TimelineEntries\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TimelineEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Zaman Çizelgesi Kaydı')
                ->description('Hakkımda sayfasındaki zaman çizelgesinde bir satır. Sıralamayı listede sürükleyerek değiştirebilirsin.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('period')
                            ->label('Dönem')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('2024')
                            ->helperText('Yıl, aralık ya da serbest metin: "2024", "2020 – 2022", "Şu an".'),

                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Tam zamanlı freelance'),
                    ]),

                    Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('İsteğe bağlı. Tek cümlelik kısa bir açıklama.')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
