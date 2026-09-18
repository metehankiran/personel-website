<?php

namespace App\Filament\Resources\ServiceAreas\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceAreaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Bölge')
                ->description('Sitedeki "Hizmet Bölgeleri" sayfasında (/hizmet-bolgeleri) görünür ve arama motorlarına hizmet verdiğin yer olarak bildirilir.')
                ->schema([
                    TextInput::make('name')
                        ->label('Bölge adı')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->placeholder('Tavşanlı'),

                    TextInput::make('province')
                        ->label('İl')
                        ->required()
                        ->maxLength(255)
                        ->default('Kütahya')
                        ->helperText('Sayfa başlığı ve açıklaması buradaki illerden oluşur.'),

                    Toggle::make('is_published')
                        ->label('Yayında')
                        ->default(true),
                ]),

            Section::make('Yerel içerik')
                ->description('Her bölgeye o bölgeye özgü bir şey yaz. Yalnızca ilçe adı değişen metinler arama motorlarında kopya sayfa muamelesi görür.')
                ->schema([
                    TextInput::make('summary')
                        ->label('Özet')
                        ->maxLength(255)
                        ->helperText('Bölge adının altında görünen tek cümle.'),

                    Textarea::make('description')
                        ->label('Açıklama')
                        ->rows(7)
                        ->maxLength(2000)
                        ->helperText('Bölgenin ekonomisi, oradaki işletmelerin ihtiyacı ve varsa o bölgeden bir işin. Satır sonları korunur.'),

                    TagsInput::make('sectors')
                        ->label('Kimlerle çalışıyorum')
                        ->placeholder('Sektör ekle')
                        ->helperText('Bölgede öne çıkan sektörler; her birini yazıp Enter\'a bas.'),
                ]),
        ])->columns(1);
    }
}
