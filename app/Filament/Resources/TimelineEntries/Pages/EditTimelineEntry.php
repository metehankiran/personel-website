<?php

namespace App\Filament\Resources\TimelineEntries\Pages;

use App\Filament\Resources\TimelineEntries\TimelineEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimelineEntry extends EditRecord
{
    protected static string $resource = TimelineEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
