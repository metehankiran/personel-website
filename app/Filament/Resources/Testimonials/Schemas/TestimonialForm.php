<?php

namespace App\Filament\Resources\Testimonials\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TestimonialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Referans Bilgileri')
                ->description('Referanslar sayfasında görüntülenen müşteri yorumunu yapılandırın. İlk sıradaki yorum öne çıkarılır (büyük kart).')
                ->schema([
                    FileUpload::make('avatar')
                        ->label('Profil Fotoğrafı')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('testimonials')
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['1:1', '4:3', '3:1'])
                        ->imageEditorMode(2)
                        ->helperText('Önerilen oran 1:1 (kare). Yükledikten sonra kalem ikonuyla hazır oran düğmelerinden kırpabilirsin.')
                        ->columnSpanFull(),

                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ad Soyad')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('title')
                            ->label('Ünvan')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Senior Developer'),

                        TextInput::make('company')
                            ->label('Şirket')
                            ->maxLength(255)
                            ->helperText('İsteğe bağlı.')
                            ->columnSpanFull(),
                    ]),

                    Textarea::make('body')
                        ->label('Yorum')
                        ->required()
                        ->rows(5)
                        ->maxLength(2000)
                        ->helperText('Referansın söylediği yorum metni.')
                        ->columnSpanFull(),

                    Select::make('rating')
                        ->label('Puan')
                        ->options([5 => '5 yıldız', 4 => '4 yıldız', 3 => '3 yıldız', 2 => '2 yıldız', 1 => '1 yıldız'])
                        ->placeholder('Puan yok')
                        ->helperText('Yalnızca müşterinin gerçekten verdiği puanı gir. Boş bırakılırsa yıldız gösterilmez.'),
                ]),
        ])->columns(1);
    }
}
