<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('pages.projects.index');
    }

    public function show(string $slug): View
    {
        return view('pages.projects.show', ['slug' => $slug]);
    }
}
