<?php

namespace App\Filament\Resources\ServiceAreas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceAreasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Bölge')
                    ->searchable()
                    ->weight('semibold'),

                TextColumn::make('province')
                    ->label('İl')
                    ->badge(),

                TextColumn::make('summary')
                    ->label('Özet')
                    ->limit(90)
                    ->wrap()
                    ->toggleable(),

                IconColumn::make('is_published')
                    ->label('Yayında')
                    ->boolean(),
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
