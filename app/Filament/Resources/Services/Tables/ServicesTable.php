<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Başlık')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('pricing')
                    ->label('Fiyat')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('duration')
                    ->label('Süre')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('badge')
                    ->label('Rozet')
                    ->placeholder('Hizmet')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('features_count')
                    ->label('Madde')
                    ->state(fn ($record): int => is_array($record->features) ? count($record->features) : 0)
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
