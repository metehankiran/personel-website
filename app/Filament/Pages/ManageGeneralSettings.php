<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class ManageGeneralSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = GeneralSettings::class;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $title = 'General';

    protected static ?int $navigationSort = 1;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('site_title')
                    ->label('Site Title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('site_description')
                    ->label('Site Description')
                    ->maxLength(500),
                TextInput::make('author_name')
                    ->label('Author Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('author_email')
                    ->label('Author Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('author_phone')
                    ->label('Author Phone')
                    ->tel()
                    ->maxLength(50),
                TextInput::make('author_address')
                    ->label('Author Address')
                    ->maxLength(500),
                TextInput::make('logo_path')
                    ->label('Logo Path')
                    ->maxLength(255),
                TextInput::make('favicon_path')
                    ->label('Favicon Path')
                    ->maxLength(255),
                TextInput::make('cv_path')
                    ->label('CV File Path')
                    ->maxLength(255),
            ]);
    }
}
