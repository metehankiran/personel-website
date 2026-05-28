<?php

namespace App\Filament\Resources\Brands\Pages;

use App\Filament\Resources\Brands\BrandResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateBrand extends CreateRecord
{
    protected static string $resource = BrandResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
