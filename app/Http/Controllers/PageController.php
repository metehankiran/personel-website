<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ContactSubject;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Page;
use App\Models\Service;
use App\Models\Skill;
use App\Settings\GeneralSettings;
use App\Settings\SocialSettings;
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
        $settings = app(GeneralSettings::class);

        return view('pages.cv', [
            'experiences' => Experience::ordered()->get(),
            'skills' => Skill::ordered()->get(),
            'educations' => Education::ordered()->get(),
            'languages' => Language::ordered()->get(),
            'cvPath' => $settings->cv_path ?: null,
        ]);
    }

    public function contact(): View
    {
        return view('pages.contact', [
            'general' => app(GeneralSettings::class),
            'social' => app(SocialSettings::class),
            'subjects' => ContactSubject::cases(),
        ]);
    }

    public function show(Page $page): View
    {
        return view('pages.page', ['page' => $page]);
    }
}
