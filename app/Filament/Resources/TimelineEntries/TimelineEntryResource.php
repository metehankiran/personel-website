<?php

namespace App\Filament\Resources\TimelineEntries;

use App\Filament\Resources\TimelineEntries\Pages\CreateTimelineEntry;
use App\Filament\Resources\TimelineEntries\Pages\EditTimelineEntry;
use App\Filament\Resources\TimelineEntries\Pages\ListTimelineEntries;
use App\Filament\Resources\TimelineEntries\Schemas\TimelineEntryForm;
use App\Filament\Resources\TimelineEntries\Tables\TimelineEntriesTable;
use App\Models\TimelineEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TimelineEntryResource extends Resource
{
    protected static ?string $model = TimelineEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|UnitEnum|null $navigationGroup = 'Hakkımda';

    protected static ?int $navigationSort = 6;

    protected static ?string $modelLabel = 'Zaman Çizelgesi Kaydı';

    protected static ?string $pluralModelLabel = 'Zaman Çizelgesi';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TimelineEntryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimelineEntriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTimelineEntries::route('/'),
            'create' => CreateTimelineEntry::route('/create'),
            'edit' => EditTimelineEntry::route('/{record}/edit'),
        ];
    }
}
