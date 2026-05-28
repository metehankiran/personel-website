<?php

namespace App\Filament\Resources\Subscribers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Abone Bilgileri')
                ->description('Bülten aboneleri. Token sistem tarafından otomatik üretilir; manuel düzenleme önerilmez.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Pasifleştirilirse bülten gönderilmez.'),
                    ]),

                    TextInput::make('token')
                        ->label('Token')
                        ->disabled()
                        ->dehydrated(false)
                        ->helperText('Sistemde otomatik üretilir. Unsubscribe linklerinde kullanılır.')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
