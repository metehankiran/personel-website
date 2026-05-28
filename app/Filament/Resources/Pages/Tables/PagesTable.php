<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PagesTable
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

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->color('gray'),

                IconColumn::make('is_published')
                    ->label('Yayın')
                    ->boolean(),

                IconColumn::make('show_footer')
                    ->label('Footer')
                    ->boolean()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title', 'asc')
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
