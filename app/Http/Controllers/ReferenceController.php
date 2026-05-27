<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Testimonial;
use Illuminate\Contracts\View\View;

class ReferenceController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.references', [
            'testimonials' => Testimonial::ordered()->get(),
            'brands' => Brand::ordered()->get(),
        ]);
    }
}
