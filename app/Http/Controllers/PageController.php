<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Service;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function services(): View
    {
        return view('pages.services', [
            'services' => Service::ordered()->get(),
        ]);
    }

    public function cv(): View
    {
        return view('pages.cv');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function show(Page $page): View
    {
        return view('pages.page', ['page' => $page]);
    }
}
