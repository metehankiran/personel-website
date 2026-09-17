<?php

namespace App\Filament\Pages;

use App\Settings\SocialSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSocialSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static string $settings = SocialSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Ayarlar';

    protected static ?string $title = 'Sosyal Medya';

    protected static ?int $navigationSort = 3;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Profiller')
                    ->description('Üst menüde, alt bilgide ve iletişim sayfasında gösterilen hesaplar. Boş bırakılan hesap sitede görünmez.')
                    ->aside()
                    ->columns(2)
                    ->schema([
                        TextInput::make('github_url')
                            ->label('GitHub')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://github.com/kullanici'),
                        TextInput::make('linkedin_url')
                            ->label('LinkedIn')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://linkedin.com/in/kullanici'),
                        TextInput::make('twitter_url')
                            ->label('Twitter / X')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://x.com/kullanici'),
                        TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/@kanal'),
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://instagram.com/kullanici'),
                        TextInput::make('bluesky_url')
                            ->label('Bluesky')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://bsky.app/profile/kullanici'),
                    ]),
            ]);
    }
}
