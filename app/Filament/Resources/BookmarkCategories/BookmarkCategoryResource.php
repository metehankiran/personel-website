<?php

namespace App\Filament\Resources\BookmarkCategories;

use App\Filament\Resources\BookmarkCategories\Pages\CreateBookmarkCategory;
use App\Filament\Resources\BookmarkCategories\Pages\EditBookmarkCategory;
use App\Filament\Resources\BookmarkCategories\Pages\ListBookmarkCategories;
use App\Filament\Resources\BookmarkCategories\RelationManagers\BookmarksRelationManager;
use App\Filament\Resources\BookmarkCategories\Schemas\BookmarkCategoryForm;
use App\Filament\Resources\BookmarkCategories\Tables\BookmarkCategoriesTable;
use App\Models\BookmarkCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class BookmarkCategoryResource extends Resource
{
    protected static ?string $model = BookmarkCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Sistem';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'Yer İmi Kategorisi';

    protected static ?string $pluralModelLabel = 'Yer İmi Kategorileri';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BookmarkCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookmarkCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            BookmarksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookmarkCategories::route('/'),
            'create' => CreateBookmarkCategory::route('/create'),
            'edit' => EditBookmarkCategory::route('/{record}/edit'),
        ];
    }
}
