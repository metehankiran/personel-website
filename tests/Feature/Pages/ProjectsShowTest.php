<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a project detail page for any slug', function () {
    $project = Project::factory()
        ->for(ProjectCategory::factory(), 'category')
        ->create([
            'title' => 'Karavela',
            'slug' => 'karavela',
            'description' => 'Multi-tenant SaaS platformu',
        ]);

    $this->get(route('projects.show', 'karavela'))
        ->assertOk()
        ->assertViewIs('pages.projects.show');
});

it('shows project detail content', function () {
    $project = Project::factory()
        ->for(ProjectCategory::factory(), 'category')
        ->create([
            'title' => 'Karavela',
            'slug' => 'karavela',
            'description' => 'Multi-tenant SaaS platformu',
        ]);

    $this->get(route('projects.show', 'karavela'))
        ->assertSee('Karavela', escape: false)
        ->assertSee('Multi-tenant', escape: false);
});
