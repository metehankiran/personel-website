<?php

namespace App\Filament\Resources\BookmarkCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookmarkCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Ad')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('bookmarks_count')
                    ->label('Yer İmi Sayısı')
                    ->counts('bookmarks')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
