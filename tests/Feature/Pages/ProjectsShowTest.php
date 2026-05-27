<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders a project detail page', function () {
    $project = Project::factory()
        ->for(ProjectCategory::factory(), 'category')
        ->create(['slug' => 'karavela']);

    $this->get(route('projects.show', $project))
        ->assertOk()
        ->assertViewIs('pages.projects.show');
});

it('shows project detail content', function () {
    $category = ProjectCategory::factory()->create(['name' => 'SaaS']);
    $project = Project::factory()->for($category, 'category')->create([
        'title' => 'Karavela',
        'slug' => 'karavela',
        'description' => 'Multi-tenant e-ticaret SaaS.',
        'client' => 'Karavela Inc.',
        'year' => 2024,
        'duration' => '5 ay',
        'role' => 'Lead developer',
        'stack' => ['Laravel', 'Vue 3', 'PostgreSQL'],
    ]);

    $this->get(route('projects.show', $project))
        ->assertSee('Karavela', escape: false)
        ->assertSee('Multi-tenant', escape: false)
        ->assertSee('SaaS', escape: false)
        ->assertSee('Karavela Inc.', escape: false)
        ->assertSee('2024', escape: false)
        ->assertSee('5 ay', escape: false)
        ->assertSee('Lead developer', escape: false)
        ->assertSee('Laravel', escape: false);
});

it('shows project stats when available', function () {
    $project = Project::factory()
        ->for(ProjectCategory::factory(), 'category')
        ->create([
            'slug' => 'karavela',
            'stats' => [
                ['label' => 'Aktif Kiracı', 'value' => '200+'],
                ['label' => 'Aylık İstek', 'value' => '2.4M'],
            ],
        ]);

    $this->get(route('projects.show', $project))
        ->assertSee('200+', escape: false)
        ->assertSee('Aktif Kiracı', escape: false)
        ->assertSee('2.4M', escape: false);
});

it('shows related projects', function () {
    $category = ProjectCategory::factory()->create();
    $main = Project::factory()->for($category, 'category')->create(['slug' => 'main', 'sort_order' => 1]);
    $related = Project::factory()->for($category, 'category')->create(['title' => 'İlgili Proje', 'sort_order' => 2]);

    $this->get(route('projects.show', $main))
        ->assertSee('İlgili Proje', escape: false);
});
