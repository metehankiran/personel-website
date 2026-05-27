<?php

namespace App\Filament\Pages;

use App\Settings\SocialSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSocialSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShare;

    protected static string $settings = SocialSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'Social Media';

    protected static ?int $navigationSort = 3;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('github_url')
                    ->label('GitHub')
                    ->url()
                    ->maxLength(255),
                TextInput::make('linkedin_url')
                    ->label('LinkedIn')
                    ->url()
                    ->maxLength(255),
                TextInput::make('twitter_url')
                    ->label('Twitter / X')
                    ->url()
                    ->maxLength(255),
                TextInput::make('youtube_url')
                    ->label('YouTube')
                    ->url()
                    ->maxLength(255),
                TextInput::make('instagram_url')
                    ->label('Instagram')
                    ->url()
                    ->maxLength(255),
                TextInput::make('bluesky_url')
                    ->label('Bluesky')
                    ->url()
                    ->maxLength(255),
            ]);
    }
}
