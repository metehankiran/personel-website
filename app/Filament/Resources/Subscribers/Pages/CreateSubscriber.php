<?php

namespace App\Filament\Resources\Subscribers\Pages;

use App\Filament\Resources\Subscribers\SubscriberResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateSubscriber extends CreateRecord
{
    protected static string $resource = SubscriberResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;
}
