<?php

namespace App\Models;

use App\Enums\EducationDegree;
use Database\Factories\EducationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    /** @use HasFactory<EducationFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'degree' => EducationDegree::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }
}
