<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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

                            Select::make('level')
                                ->label('Seviye')
                                ->options([
                                    1 => '1 — Başlangıç',
                                    2 => '2 — Temel',
                                    3 => '3 — Orta',
                                    4 => '4 — İleri',
                                    5 => '5 — Uzman',
                                ])
                                ->required()
                                ->native(false),
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
