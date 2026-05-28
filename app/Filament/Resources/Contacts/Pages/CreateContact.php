<?php

namespace App\Filament\Resources\Contacts\Pages;

use App\Filament\Resources\Contacts\ContactResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
