<?php

namespace App\Filament\Resources\Languages\Schemas;

use App\Enums\LanguageLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LanguageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Dil Bilgisi')
                ->description('Özgeçmiş sayfasında listelenen dil ve yetkinlik seviyesini yapılandırın.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Dil')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('English'),

                        Select::make('level')
                            ->label('Seviye')
                            ->options(LanguageLevel::class)
                            ->required()
                            ->native(false),
                    ]),
                ]),
        ])->columns(1);
    }
}
