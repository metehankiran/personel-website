<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceArea;
use Illuminate\Contracts\View\View;

class ServiceAreaController extends Controller
{
    public function index(): View
    {
        return view('pages.service-areas.index', [
            'areas' => ServiceArea::published()->ordered()->get(),
        ]);
    }

    public function show(ServiceArea $serviceArea): View
    {
        abort_unless($serviceArea->is_published, 404);

        return view('pages.service-areas.show', [
            'area' => $serviceArea,
            'services' => Service::ordered()->get(),
            'otherAreas' => ServiceArea::published()->ordered()->whereKeyNot($serviceArea->getKey())->get(['name', 'slug']),
        ]);
    }
}
