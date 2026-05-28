<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Sayfa Bilgileri')
                ->description('KVKK, çerez politikası gibi statik sayfaların yapılandırılması. Bu sayfalar /{slug} URL\'inde yayınlanır.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('KVKK')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL\'de görünür: /{slug}'),
                    ]),
                ]),

            Section::make('İçerik')
                ->schema([
                    RichEditor::make('body')
                        ->label('Sayfa İçeriği')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Görüntüleme Ayarları')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('is_published')
                            ->label('Yayında')
                            ->helperText('Kapalıysa /{slug} adresi 404 döner.'),

                        Toggle::make('show_footer')
                            ->label('Footer Göster')
                            ->helperText('Sayfa altında footer bileşeni gösterilsin mi?')
                            ->default(true),
                    ]),
                ]),
        ])->columns(1);
    }
}
