<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Temel Bilgiler')
                ->description('Yazının başlığı, slug\'ı ve kategorisi.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->label('Başlık')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL\'lerde kullanılır.'),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->columnSpan(1),

                        Select::make('tags')
                            ->label('Etiketler')
                            ->multiple()
                            ->relationship('tags', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->helperText('Birden fazla etiket seçilebilir.')
                            ->columnSpan(1),
                    ]),
                ]),

            Section::make('İçerik')
                ->schema([
                    Textarea::make('excerpt')
                        ->label('Özet')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Liste sayfasında görünen kısa özet (boş bırakılırsa otomatik üretilir).')
                        ->columnSpanFull(),

                    RichEditor::make('body')
                        ->label('İçerik')
                        ->required()
                        ->columnSpanFull(),
                ]),

            Section::make('Kapak Görseli')
                ->schema([
                    FileUpload::make('cover_image')
                        ->label('Kapak')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('posts')
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['16:9', '4:3', '3:2', '1:1'])
                        ->imageEditorMode(2)
                        ->helperText('Önerilen oran 16:9.')
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            Section::make('Yayın Durumu')
                ->description('Yayın açıldığında published_at otomatik olarak şimdiye ayarlanır (boş bırakılırsa). İleri tarih seçerek planlı yayın yapabilirsin.')
                ->schema([
                    Grid::make(2)->schema([
                        Toggle::make('is_published')
                            ->label('Yayında')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, bool $state): void {
                                if ($state && blank($get('published_at'))) {
                                    $set('published_at', now()->format('Y-m-d H:i:s'));
                                }
                            }),

                        DateTimePicker::make('published_at')
                            ->label('Yayın Tarihi')
                            ->native(false)
                            ->displayFormat('d M Y H:i')
                            ->helperText('İleri tarih = planlı yayın.'),
                    ]),
                ]),
        ])->columns(1);
    }
}
