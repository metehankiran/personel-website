<?php

namespace App\Filament\Resources\TimelineEntries\Pages;

use App\Filament\Resources\TimelineEntries\TimelineEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTimelineEntries extends ListRecords
{
    protected static string $resource = TimelineEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
