<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Page;
use App\Models\Post;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use App\Models\TimelineEntry;
use App\Settings\AboutSettings;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'posts' => Post::published()->latest('published_at')->with('category')->take(3)->get(),
            'testimonials' => Testimonial::ordered()->take(8)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', [
            'about' => app(AboutSettings::class),
            'timeline' => TimelineEntry::ordered()->get(),
            'languages' => Language::ordered()->pluck('name'),
        ]);
    }

    public function services(): View
    {
        return view('pages.services', [
            'services' => Service::ordered()->get(),
        ]);
    }

    public function cv(): View
    {
        $settings = app(GeneralSettings::class);

        return view('pages.cv', [
            'experiences' => Experience::ordered()->get(),
            'skills' => Skill::ordered()->get(),
            'educations' => Education::ordered()->get(),
            'languages' => Language::ordered()->get(),
            'cvPath' => $settings->cv_path ?: null,
            'cvDownloadName' => $settings->cvDownloadName(),
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'general' => app(GeneralSettings::class),
            'social' => app(SocialSettings::class),
        ]);
    }

    public function show(Page $page): View
    {
        abort_unless($page->isVisibleToCurrentVisitor(), 404);

        return view('pages.page', ['page' => $page]);
    }
}
