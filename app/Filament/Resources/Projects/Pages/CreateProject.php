<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
