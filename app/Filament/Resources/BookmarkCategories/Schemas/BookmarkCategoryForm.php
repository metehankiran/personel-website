<?php

namespace App\Filament\Resources\BookmarkCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookmarkCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Yer İmi Kategorisi')
                ->description('Yer imlerini gruplamak için kullanılan kategori (örn. Geliştirme, Tasarım, Okuma).')
                ->schema([
                    TextInput::make('name')
                        ->label('Ad')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('Geliştirme')
                        ->columnSpanFull(),

                    TextInput::make('description')
                        ->label('Açıklama')
                        ->maxLength(255)
                        ->helperText('İsteğe bağlı kısa açıklama.')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
