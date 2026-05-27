<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Contracts\View\View;

class SkillController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.stack', [
            'skills' => Skill::ordered()->get(),
        ]);
    }
}
