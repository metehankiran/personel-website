<?php

namespace App\Filament\Resources\Contacts\Tables;

use App\Enums\ContactSubject;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('Okundu')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Gönderen')
                    ->searchable()
                    ->sortable()
                    ->weight(fn ($record) => $record->is_read ? 'normal' : 'semibold'),

                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),

                TextColumn::make('subject')
                    ->label('Konu')
                    ->badge()
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Mesaj')
                    ->limit(60)
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('subject')
                    ->label('Konu')
                    ->options(ContactSubject::class),

                Filter::make('unread')
                    ->label('Sadece okunmamış')
                    ->query(fn (Builder $query) => $query->where('is_read', false)),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Okundu olarak işaretle')
                        ->icon('heroicon-o-check')
                        ->action(fn (Collection $records) => $records->each->update(['is_read' => true]))
                        ->deselectRecordsAfterCompletion(),

                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
