<?php

namespace App\Filament\Resources\Subscribers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('semibold'),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Abone Olma')
                    ->dateTime('d M Y H:i')
                    ->since()
                    ->sortable(),

                TextColumn::make('token')
                    ->label('Token')
                    ->limit(16)
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Durum')
                    ->placeholder('Hepsi')
                    ->trueLabel('Aktif')
                    ->falseLabel('Pasif'),

                Filter::make('active_only')
                    ->label('Yalnızca aktif')
                    ->query(fn (Builder $query) => $query->where('is_active', true))
                    ->default(),
            ])
            ->defaultSort('created_at', 'desc')
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
