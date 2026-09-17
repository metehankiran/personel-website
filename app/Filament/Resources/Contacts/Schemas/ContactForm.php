<?php

namespace App\Filament\Resources\Contacts\Schemas;

use App\Models\Service;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class ContactForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Gönderen')
                ->description('Bu kayıt frontend formundan otomatik oluşur. Alanlar değişiklik için açıktır ama genelde sadece okunur.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('E-posta')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone')
                            ->label('Telefon')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('subject')
                            ->label('Konu')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Mesaj gönderilirken seçilen konunun o anki adı.'),

                        Select::make('service_id')
                            ->label('Hizmet')
                            ->relationship('service', 'title', fn (Builder $query) => $query->withTrashed())
                            ->getOptionLabelFromRecordUsing(fn (Service $record): string => $record->trashed() ? "{$record->title} (silindi)" : $record->title)
                            ->placeholder('Bir hizmetle ilgili değil')
                            ->native(false),
                    ]),
                ]),

            Section::make('Mesaj')
                ->schema([
                    Textarea::make('message')
                        ->label('Mesaj')
                        ->required()
                        ->rows(8)
                        ->columnSpanFull(),
                ]),

            Section::make('Durum')
                ->schema([
                    Toggle::make('is_read')
                        ->label('Okundu')
                        ->helperText('Açıldığında sidebar bildirimi (badge) azalır.'),
                ]),
        ])->columns(1);
    }
}
