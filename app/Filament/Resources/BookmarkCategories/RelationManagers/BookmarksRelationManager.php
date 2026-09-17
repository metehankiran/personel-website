<?php

namespace App\Filament\Resources\BookmarkCategories\RelationManagers;

use App\Filament\Resources\Bookmarks\BookmarkResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class BookmarksRelationManager extends RelationManager
{
    protected static string $relationship = 'bookmarks';

    protected static ?string $title = 'Yer İmleri';

    protected static ?string $relatedResource = BookmarkResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->url(fn (): string => BookmarkResource::getUrl('create', ['category_id' => $this->getOwnerRecord()->getKey()])),
            ]);
    }
}
