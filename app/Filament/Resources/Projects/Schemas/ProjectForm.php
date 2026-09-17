<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\ProjectCategory;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Temel Bilgiler')
                ->description('Proje listesinde ve detay sayfasında görünen ana meta veriler.')
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
                            ->helperText('URL\'lerde kullanılır. Başlıktan otomatik üretilir.'),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            // Preselected when the page is opened from a category's relation table.
                            ->default(fn (): ?int => ProjectCategory::whereKey(request()->integer('category_id'))->value('id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Ad')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->maxLength(255),
                            ]),

                        TextInput::make('year')
                            ->label('Yıl')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(2000)
                            ->maxValue(2100)
                            ->placeholder('2024'),
                    ]),
                ]),

            Section::make('Müşteri & Rol')
                ->description('Opsiyonel bağlam bilgileri.')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('client')
                            ->label('Müşteri')
                            ->maxLength(255),

                        TextInput::make('duration')
                            ->label('Süre')
                            ->maxLength(255)
                            ->placeholder('5 ay'),

                        TextInput::make('role')
                            ->label('Rol')
                            ->maxLength(255)
                            ->placeholder('Lead developer'),
                    ]),
                ])
                ->collapsible(),

            Section::make('Açıklama')
                ->schema([
                    Textarea::make('description')
                        ->label('Kısa Açıklama')
                        ->required()
                        ->rows(3)
                        ->maxLength(2000)
                        ->helperText('Liste sayfasında görünen özet metin.')
                        ->columnSpanFull(),

                    RichEditor::make('body')
                        ->label('Detay (Case Study)')
                        ->helperText('Detay sayfasında görünen tam içerik.')
                        ->columnSpanFull(),
                ]),

            Section::make('Kapak Görseli')
                ->schema([
                    FileUpload::make('cover_image')
                        ->label('Kapak')
                        ->image()
                        ->disk('public')
                        ->visibility('public')
                        ->directory('projects')
                        ->imageEditor()
                        ->imageEditorAspectRatioOptions(['16:9', '4:3', '3:2', '1:1'])
                        ->imageEditorMode(2)
                        ->helperText('Önerilen oran 16:9. Yükledikten sonra kalem ikonuyla kırpabilirsin.')
                        ->columnSpanFull(),
                ])
                ->collapsible(),

            Section::make('Teknoloji Yığını')
                ->schema([
                    TagsInput::make('stack')
                        ->label('Stack')
                        ->required()
                        ->placeholder('Enter ile teknoloji ekle')
                        ->helperText('Projede kullanılan teknolojiler (Laravel, Vue 3, vb.).')
                        ->columnSpanFull(),
                ]),

            Section::make('Ek Veri')
                ->description('İsteğe bağlı anahtar-değer çiftleri ve istatistikler.')
                ->schema([
                    // Pairs in a list, not a key value map: MySQL json columns do not keep the key order of a map.
                    Repeater::make('extras')
                        ->label('Ekstra Bilgiler')
                        ->table([
                            TableColumn::make('Etiket')->markAsRequired(),
                            TableColumn::make('Değer')->markAsRequired(),
                        ])
                        ->schema([
                            TextInput::make('label')->label('Etiket')->required()->placeholder('Müşteri'),
                            TextInput::make('value')->label('Değer')->required()->placeholder('Karavela A.Ş.'),
                        ])
                        ->addActionLabel('Bilgi ekle')
                        ->defaultItems(0)
                        ->columnSpanFull(),

                    Repeater::make('stats')
                        ->label('İstatistikler')
                        ->table([
                            TableColumn::make('Metrik')->markAsRequired(),
                            TableColumn::make('Değer')->markAsRequired(),
                        ])
                        ->schema([
                            TextInput::make('label')->label('Metrik')->required()->placeholder('Aktif Kiracı'),
                            TextInput::make('value')->label('Değer')->required()->placeholder('200+'),
                        ])
                        ->addActionLabel('İstatistik ekle')
                        ->defaultItems(0)
                        ->columnSpanFull(),
                ])
                ->collapsed(),
        ])->columns(1);
    }
}
