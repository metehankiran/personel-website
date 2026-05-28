<?php

namespace App\Filament\Resources\Bookmarks\Pages;

use App\Filament\Resources\Bookmarks\BookmarkResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateBookmark extends CreateRecord
{
    protected static string $resource = BookmarkResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
