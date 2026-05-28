<?php

namespace App\Filament\Resources\Languages\Pages;

use App\Filament\Resources\Languages\LanguageResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateLanguage extends CreateRecord
{
    protected static string $resource = LanguageResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
