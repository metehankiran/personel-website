<?php

namespace App\Filament\Resources\Education\Schemas;

use App\Enums\EducationDegree;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Eğitim Bilgisi')
                ->description('Özgeçmiş sayfasında listelenen eğitim kaydını yapılandırın.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('school')
                            ->label('Okul')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Boğaziçi University'),

                        Select::make('degree')
                            ->label('Derece')
                            ->options(EducationDegree::class)
                            ->required()
                            ->native(false),

                        TextInput::make('field')
                            ->label('Alan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Computer Science'),

                        TextInput::make('gpa')
                            ->label('GPA / Not Ortalaması')
                            ->maxLength(20)
                            ->placeholder('3.45/4')
                            ->helperText('İsteğe bağlı.'),

                        DatePicker::make('start_date')
                            ->label('Başlangıç Tarihi')
                            ->required()
                            ->native(false)
                            ->displayFormat('d M Y'),

                        DatePicker::make('end_date')
                            ->label('Bitiş Tarihi')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->helperText('Boş bırakılırsa "Devam ediyor" kabul edilir.'),
                    ]),
                ]),
        ])->columns(1);
    }
}
