<?php

namespace App\Filament\Resources\Bookmarks\Pages;

use App\Filament\Resources\Bookmarks\BookmarkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditBookmark extends EditRecord
{
    protected static string $resource = BookmarkResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
