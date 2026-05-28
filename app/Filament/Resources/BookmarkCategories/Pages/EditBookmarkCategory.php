<?php

namespace App\Filament\Resources\BookmarkCategories\Pages;

use App\Filament\Resources\BookmarkCategories\BookmarkCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditBookmarkCategory extends EditRecord
{
    protected static string $resource = BookmarkCategoryResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
