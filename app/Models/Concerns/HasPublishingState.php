<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Content is public when it is switched on and its publish date has passed.
 * A future date schedules it; a missing date keeps it private.
 */
trait HasPublishingState
{
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->is_published
            && $this->published_at !== null
            && $this->published_at->lte(now());
    }

    /**
     * Visitors only see published content; a signed in user may preview the rest.
     */
    public function isVisibleToCurrentVisitor(): bool
    {
        return $this->isPublished() || auth()->check();
    }
}
