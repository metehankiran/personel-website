<?php

namespace App\Filament\Resources\Bookmarks\Schemas;

use App\Models\BookmarkCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookmarkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Yer İmi Bilgileri')
                ->description('Kişisel yer imi (link) ekle. URL ve kategori zorunludur.')
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            // Preselected when the page is opened from a category's relation table.
                            ->default(fn (): ?int => BookmarkCategory::whereKey(request()->integer('category_id'))->value('id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Ad')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('description')
                                    ->label('Açıklama')
                                    ->maxLength(255),
                            ]),

                        TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required()
                            ->maxLength(500)
                            ->placeholder('https://example.com'),
                    ]),

                    TextInput::make('description')
                        ->label('Açıklama')
                        ->maxLength(255)
                        ->helperText('İsteğe bağlı kısa açıklama.')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
