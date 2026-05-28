<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kategori Bilgileri')
                ->description('Blog yazılarını gruplamak için kategori. Hiyerarşik (alt kategori) destekler.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('name')
                            ->label('Ad')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Laravel')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL\'lerde kullanılır.'),

                        Select::make('parent_id')
                            ->label('Üst Kategori')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query, ?Category $record) => $record
                                    ? $query->where('id', '!=', $record->id)
                                    : $query,
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->placeholder('Üst seviye (root)')
                            ->helperText('Boş bırakılırsa bu kategori en üst seviye olur.')
                            ->columnSpanFull(),
                    ]),
                ]),
        ])->columns(1);
    }
}
