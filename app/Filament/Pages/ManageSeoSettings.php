<?php

namespace App\Filament\Pages;

use App\Settings\SeoSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageSeoSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected static string $settings = SeoSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'SEO';

    protected static ?int $navigationSort = 2;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('meta_description')
                    ->label('Meta Description')
                    ->maxLength(160),
                TextInput::make('og_image_path')
                    ->label('OG Image Path')
                    ->maxLength(255),
                TextInput::make('google_analytics_id')
                    ->label('Google Analytics ID')
                    ->placeholder('G-XXXXXXXXXX')
                    ->maxLength(50),
                TextInput::make('google_search_console_id')
                    ->label('Google Search Console ID')
                    ->maxLength(100),
            ]);
    }
}
