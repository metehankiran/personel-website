<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the projects index page', function () {
    $this->get(route('projects'))
        ->assertOk()
        ->assertViewIs('pages.projects.index');
});

it('shows the projects hero content', function () {
    $this->get(route('projects'))
        ->assertSee('Projeler', escape: false)
        ->assertSee('Yaptığım işler', escape: false);
});

it('displays projects from database', function () {
    $category = ProjectCategory::factory()->create(['name' => 'SaaS']);
    Project::factory()->for($category, 'category')->create([
        'title' => 'Karavela',
        'description' => 'Multi-tenant e-ticaret SaaS.',
        'year' => 2024,
        'stack' => ['Laravel', 'Vue 3', 'PostgreSQL'],
    ]);

    $this->get(route('projects'))
        ->assertSee('Karavela', escape: false)
        ->assertSee('Multi-tenant', escape: false)
        ->assertSee('2024', escape: false)
        ->assertSee('SaaS', escape: false)
        ->assertSee('Laravel', escape: false);
});

it('displays category filter buttons', function () {
    // The filters only appear when there is something to filter.
    Project::factory()->for(ProjectCategory::factory()->create(['name' => 'SaaS']), 'category')->create();
    ProjectCategory::factory()->create(['name' => 'API']);

    $this->get(route('projects'))
        ->assertSee('Hepsi', escape: false)
        ->assertSee('SaaS', escape: false)
        ->assertSee('API', escape: false);
});

it('displays projects in order', function () {
    $category = ProjectCategory::factory()->create();
    Project::factory()->for($category, 'category')->create(['title' => 'İkinci', 'sort_order' => 2]);
    Project::factory()->for($category, 'category')->create(['title' => 'Birinci', 'sort_order' => 1]);

    $response = $this->get(route('projects'));

    $response->assertSeeInOrder(['Birinci', 'İkinci']);
});
