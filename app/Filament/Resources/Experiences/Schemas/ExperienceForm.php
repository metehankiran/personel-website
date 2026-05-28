<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('İş Deneyimi')
                ->description('Özgeçmiş sayfasında listelenen pozisyon bilgilerini girin.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Pozisyon')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Senior Backend Developer'),

                        TextInput::make('company')
                            ->label('Şirket')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Acme Inc.'),

                        DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y'),

                        DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->helperText('Boş bırakılırsa "Halen devam ediyor" anlamına gelir.'),
                    ]),

                    Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(4)
                        ->maxLength(2000)
                        ->helperText('İsteğe bağlı. Pozisyondaki sorumluluklarını/projelerini özetle.')
                        ->columnSpanFull(),
                ]),
        ])->columns(1);
    }
}
