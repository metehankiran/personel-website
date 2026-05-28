<?php

namespace App\Filament\Resources\BookmarkCategories\Pages;

use App\Filament\Resources\BookmarkCategories\BookmarkCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookmarkCategories extends ListRecords
{
    protected static string $resource = BookmarkCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
