<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Grup Adı')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('description')
                    ->label('Açıklama')
                    ->limit(60)
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('items_count')
                    ->label('Teknoloji Sayısı')
                    ->state(fn ($record): int => is_array($record->items) ? count($record->items) : 0)
                    ->badge(),

                TextColumn::make('items_preview')
                    ->label('İçerik')
                    ->state(fn ($record): string => is_array($record->items)
                        ? collect($record->items)->pluck('name')->filter()->join(' · ')
                        : ''
                    )
                    ->limit(60)
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
