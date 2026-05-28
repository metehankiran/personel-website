<?php

namespace App\Filament\Resources\BookmarkCategories\Pages;

use App\Filament\Resources\BookmarkCategories\BookmarkCategoryResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateBookmarkCategory extends CreateRecord
{
    protected static string $resource = BookmarkCategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
