<?php

declare(strict_types=1);

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

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

it('shows extra details stored as label and value pairs', function () {
    $project = Project::factory()
        ->for(ProjectCategory::factory(), 'category')
        ->create(['extras' => [['label' => 'Müşteri', 'value' => 'Karavela A.Ş.']]]);

    $this->get(route('projects.show', $project))->assertOk()->assertSeeInOrder(['Müşteri', 'Karavela A.Ş.']);
});

it('still renders projects whose extras and stats were saved as a key value map by the old panel field', function () {
    $project = Project::factory()->for(ProjectCategory::factory(), 'category')->create();

    DB::table('projects')->where('id', $project->id)->update([
        'extras' => json_encode(['Müşteri' => 'Karavela A.Ş.']),
        'stats' => json_encode(['Uptime' => '99.97%']),
    ]);

    $this->get(route('projects.show', $project))
        ->assertOk()
        ->assertSeeInOrder(['Müşteri', 'Karavela A.Ş.'])
        ->assertSee('Uptime')
        ->assertSee('99.97%');

    expect($project->fresh()->extras)->toBe([['label' => 'Müşteri', 'value' => 'Karavela A.Ş.']]);
});

it('turns a url in the project details into a short link', function () {
    $project = Project::factory()->for(ProjectCategory::factory(), 'category')->create([
        'extras' => [
            ['label' => 'Github', 'value' => 'https://github.com/ada/personel-website'],
            ['label' => 'Ekip', 'value' => '3 kişi'],
        ],
    ]);

    $html = $this->get(route('projects.show', $project))->assertOk()->getContent();

    expect($html)->toContain('<a href="https://github.com/ada/personel-website" target="_blank" rel="noopener"')
        ->toContain('github.com/ada/personel-website</a>')
        ->not->toContain('>https://github.com/ada/personel-website</a>')
        ->and($html)->toContain('3 kişi')
        ->and(substr_count($html, 'data-detail-row'))->toBeGreaterThanOrEqual(4);
});
