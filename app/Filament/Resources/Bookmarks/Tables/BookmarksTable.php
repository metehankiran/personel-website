<?php

namespace App\Filament\Resources\Bookmarks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookmarksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                TextColumn::make('domain')
                    ->label('Domain')
                    ->state(fn ($record): ?string => $record->domain)
                    ->color('gray')
                    ->weight('semibold'),

                TextColumn::make('url')
                    ->label('URL')
                    ->url(fn ($record): string => $record->url)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Eklenme')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload(),
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
