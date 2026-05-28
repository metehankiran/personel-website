<?php

namespace App\Filament\Resources\Skills\Pages;

use App\Filament\Resources\Skills\SkillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditSkill extends EditRecord
{
    protected static string $resource = SkillResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
