<?php

namespace App\Filament\Resources\Experiences\Pages;

use App\Filament\Resources\Experiences\ExperienceResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateExperience extends CreateRecord
{
    protected static string $resource = ExperienceResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
