<?php

namespace App\Filament\Resources\TimelineEntries\Pages;

use App\Filament\Resources\TimelineEntries\TimelineEntryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTimelineEntry extends CreateRecord
{
    protected static string $resource = TimelineEntryResource::class;
}
