<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('pages.projects.index', [
            'projects' => Project::with('category')->ordered()->get(),
            'categories' => ProjectCategory::ordered()->get(),
        ]);
    }

    public function show(Project $project): View
    {
        $project->load('category');

        return view('pages.projects.show', ['project' => $project]);
    }
}
