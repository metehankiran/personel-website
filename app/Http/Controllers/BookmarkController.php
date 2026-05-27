<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BookmarkCategory;
use Illuminate\Contracts\View\View;

class BookmarkController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.bookmarks', [
            'categories' => BookmarkCategory::with('bookmarks')->ordered()->get(),
        ]);
    }
}
