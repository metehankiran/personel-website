<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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
        return view('pages.services');
    }

    public function references(): View
    {
        return view('pages.references');
    }

    public function stack(): View
    {
        return view('pages.stack');
    }

    public function cv(): View
    {
        return view('pages.cv');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }
}
