<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Sayfa Bilgileri')
                ->description('KVKK, çerez politikası gibi statik sayfaların yapılandırılması. Bu sayfalar /sayfa/{slug} adresinde yayınlanır.')
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
                            ->helperText('URL\'de görünür: /sayfa/{slug}'),
                    ]),
                ]),

            Section::make('İçerik')
                ->schema([
                    RichEditor::make('body')
                        ->label('Sayfa İçeriği')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Yayın Durumu')
                ->description('Sayfa, "Yayında" açık olduğunda ve yayın tarihi geldiğinde ziyaretçilere açılır. İleri bir tarih seçerek planlı yayın yapabilirsin; giriş yapmışken taslakları da önizleyebilirsin.')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('is_published')
                            ->label('Yayında')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, bool $state): void {
                                if ($state && blank($get('published_at'))) {
                                    $set('published_at', now()->format('Y-m-d H:i:s'));
                                }
                            })
                            ->helperText('Kapalıysa /sayfa/{slug} adresi ziyaretçilere 404 döner.'),

                        DateTimePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->requiredIf('is_published', true)
                            ->validationMessages(['required_if' => 'Yayındaki bir sayfanın yayın tarihi olmalı.'])
                            ->helperText('İleri tarih = planlı yayın.'),
                    ]),
                ]),

            Section::make('Görüntüleme Ayarları')
                ->schema([
                    Toggle::make('show_footer')
                        ->label('Footer Göster')
                        ->helperText('Sayfa altında footer bileşeni gösterilsin mi?')
                        ->default(true),
                ]),
        ])->columns(1);
    }
}
