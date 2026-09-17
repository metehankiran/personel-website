<?php

namespace App\Filament\Resources\TimelineEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TimelineEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('period')
                    ->label('Dönem')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->placeholder('—')
                    ->limit(80)
                    ->wrap()
                    ->toggleable(),

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
