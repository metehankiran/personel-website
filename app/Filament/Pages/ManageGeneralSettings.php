<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Section;
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
                Section::make('Site')
                    ->schema([
                        TextInput::make('site_title')->label('Site Title')->required()->maxLength(255),
                        TextInput::make('site_description')->label('Site Description')->maxLength(500),
                        TextInput::make('logo_path')->label('Logo Path')->maxLength(255),
                        TextInput::make('favicon_path')->label('Favicon Path')->maxLength(255),
                    ])->columns(2),
                Section::make('Author')
                    ->schema([
                        TextInput::make('author_name')->label('Name')->required()->maxLength(255),
                        TextInput::make('author_title')->label('Title')->maxLength(255)->placeholder('Full-stack Developer'),
                        TextInput::make('author_email')->label('Email')->email()->maxLength(255),
                        TextInput::make('author_phone')->label('Phone')->tel()->maxLength(50),
                        TextInput::make('author_address')->label('Address')->maxLength(500),
                        TextInput::make('author_location')->label('Location')->maxLength(100)->placeholder('İstanbul, TR'),
                        Textarea::make('bio')->label('Bio / Summary')->rows(3),
                        TextInput::make('cv_path')->label('CV File Path')->maxLength(255),
                    ])->columns(2),
                Section::make('Homepage Hero')
                    ->schema([
                        TextInput::make('availability_status')->label('Availability Status')->maxLength(255)->placeholder('Yeni proje alıyor'),
                        Textarea::make('hero_title')->label('Hero Title (HTML allowed)')->rows(2),
                        Textarea::make('hero_subtitle')->label('Hero Subtitle')->rows(2),
                        Repeater::make('homepage_stats')
                            ->label('Homepage Stats')
                            ->schema([
                                TextInput::make('label')->required(),
                                TextInput::make('value')->required(),
                            ])->columns(2)->defaultItems(0),
                    ]),
                Section::make('Footer')
                    ->schema([
                        Textarea::make('footer_text')->label('Footer Description')->rows(2),
                    ]),
                Section::make('Legal & Contact')
                    ->schema([
                        TextInput::make('kvkk_page_slug')->label('KVKK Page Slug')->maxLength(100)->placeholder('kvkk'),
                        TextInput::make('cookie_policy_slug')->label('Cookie Policy Page Slug')->maxLength(100)->placeholder('cookie-policy'),
                        TextInput::make('google_maps_url')->label('Google Maps URL')->url()->maxLength(500),
                    ])->columns(2),
            ]);
    }
}
