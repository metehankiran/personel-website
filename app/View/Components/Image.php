<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Support\Images;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * Image with a bundled default: resolves storage paths server-side and
 * swaps to the fallback client-side if the file still fails to load.
 */
class Image extends Component
{
    public string $url;

    public string $fallbackUrl;

    public function __construct(
        public ?string $src = null,
        public string $fallback = 'cover',
        public string $alt = '',
        public string $loading = 'lazy',
    ) {
        $this->url = Images::url($src, $fallback);
        $this->fallbackUrl = Images::fallback($fallback);
    }

    public function render(): View|Closure|string
    {
        return view('components.image');
    }
}
