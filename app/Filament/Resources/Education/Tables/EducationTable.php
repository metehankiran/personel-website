<?php

namespace App\Filament\Resources\Education\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EducationTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('school')
                    ->label('Okul')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('degree')
                    ->label('Derece')
                    ->badge()
                    ->sortable(),

                TextColumn::make('field')
                    ->label('Alan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('gpa')
                    ->label('GPA')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('start_date')
                    ->label('Başlangıç')
                    ->date('M Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Bitiş')
                    ->date('M Y')
                    ->placeholder('Devam ediyor')
                    ->sortable(),

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
