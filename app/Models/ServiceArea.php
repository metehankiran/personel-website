<?php

namespace App\Models;

use Database\Factories\ServiceAreaFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceArea extends Model
{
    /** @use HasFactory<ServiceAreaFactory> */
    use HasFactory;

    protected $attributes = [
        'is_published' => true,
    ];

    protected function casts(): array
    {
        return [
            'sectors' => 'array',
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ServiceArea $area) {
            // The slug anchors the area on the page, so it is set once and survives a rename.
            $area->slug ??= Str::slug($area->name);

            // New areas go to the end; the order is then managed by dragging in the panel.
            $area->sort_order ??= (int) static::max('sort_order') + 1;
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
