<?php

namespace App\Models;

use Database\Factories\TimelineEntryFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimelineEntry extends Model
{
    /** @use HasFactory<TimelineEntryFactory> */
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (TimelineEntry $entry) {
            // New entries go to the end; the order is then managed by dragging in the panel.
            $entry->sort_order ??= (int) static::max('sort_order') + 1;
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
