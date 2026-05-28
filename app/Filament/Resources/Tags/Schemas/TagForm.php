<?php

namespace App\Filament\Resources\Tags\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Etiket Bilgileri')
                ->description('Blog yazılarını çapraz olarak işaretlemek için etiket.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Laravel')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL\'lerde kullanılır.'),
                    ]),
                ]),
        ])->columns(1);
    }
}
