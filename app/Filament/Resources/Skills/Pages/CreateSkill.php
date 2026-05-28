<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Resources\Skills\SkillResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateSkill extends CreateRecord
{
    protected static string $resource = SkillResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
