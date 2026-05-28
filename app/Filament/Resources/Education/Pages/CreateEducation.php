<?php

namespace App\Filament\Resources\Education\Pages;

use App\Filament\Resources\Education\EducationResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateEducation extends CreateRecord
{
    protected static string $resource = EducationResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
