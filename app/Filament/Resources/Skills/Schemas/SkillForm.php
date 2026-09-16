<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Yetenek Grubu')
                ->description('Stack sayfasında bir kategori (örn. Backend, Frontend, DevOps) ve bu kategoriye ait teknoloji listesini yapılandırın.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Grup Adı')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Backend'),

                        TextInput::make('description')
                            ->label('Açıklama')
                            ->maxLength(255)
                            ->placeholder('Sunucu tarafı yetenekler.')
                            ->helperText('İsteğe bağlı kısa açıklama.'),
                    ]),

                    Repeater::make('items')
                        ->label('Teknolojiler')
                        ->columnSpanFull()
                        ->schema([
                            TextInput::make('name')
                                ->label('Ad')
                                ->required()
                                ->maxLength(100)
                                ->placeholder('Laravel'),

                            TextInput::make('description')
                                ->label('Kısa Açıklama')
                                ->maxLength(255)
                                ->placeholder('Ana çerçevem'),

                            TextInput::make('since')
                                ->label('Başlangıç Yılı')
                                ->numeric()
                                ->integer()
                                ->minValue(1990)
                                ->maxValue(fn (): int => now()->year)
                                ->placeholder((string) now()->year)
                                ->helperText('Stack sayfasında kaç yıldır kullandığın olarak gösterilir. Boş bırakılabilir.'),
                        ])
                        ->columns(3)
                        ->minItems(1)
                        ->defaultItems(1)
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                        ->addActionLabel('Teknoloji Ekle'),
                ]),
        ])->columns(1);
    }
}
