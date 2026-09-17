<?php

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('list page renders for authenticated user', function () {
    Livewire::test(ListProjects::class)->assertOk();
});

test('projects appear in the table', function () {
    $projects = Project::factory()->count(3)->create();

    Livewire::test(ListProjects::class)
        ->assertCanSeeTableRecords($projects);
});

test('project can be created with all required fields', function () {
    $category = ProjectCategory::factory()->create();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'Awesome Dashboard',
            'slug' => 'awesome-dashboard',
            'category_id' => $category->id,
            'description' => 'A SaaS dashboard for analytics.',
            'year' => 2024,
            'stack' => ['Laravel', 'Vue 3', 'PostgreSQL'],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::firstWhere('slug', 'awesome-dashboard');
    expect($project)->not->toBeNull();
    expect($project->category_id)->toBe($category->id);
    expect($project->stack)->toBe(['Laravel', 'Vue 3', 'PostgreSQL']);
});

test('extras and stats are saved as ordered label and value pairs, the shape the project page reads', function () {
    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'Karavela',
            'slug' => 'karavela',
            'category_id' => ProjectCategory::factory()->create()->id,
            'description' => 'Multi-tenant e-ticaret SaaS.',
            'year' => 2024,
            'stack' => ['Laravel'],
            'extras' => [['label' => 'Müşteri', 'value' => 'Karavela A.Ş.'], ['label' => 'Ekip', 'value' => '3 kişi']],
            'stats' => [['label' => 'Uptime', 'value' => '99.97%']],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $project = Project::firstWhere('slug', 'karavela');

    expect($project->extras)->toBe([['label' => 'Müşteri', 'value' => 'Karavela A.Ş.'], ['label' => 'Ekip', 'value' => '3 kişi']])
        ->and($project->stats)->toBe([['label' => 'Uptime', 'value' => '99.97%']]);

    $this->get(route('projects.show', $project))->assertOk()->assertSeeInOrder(['Müşteri', 'Karavela A.Ş.', 'Ekip', '3 kişi']);
});

test('a project saved by the old key value field opens in the edit form and is rewritten as pairs', function () {
    $project = Project::factory()->create();
    DB::table('projects')->where('id', $project->id)->update(['extras' => json_encode(['Müşteri' => 'Karavela A.Ş.'])]);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->assertOk()
        ->call('save')
        ->assertHasNoFormErrors();

    expect(json_decode(DB::table('projects')->where('id', $project->id)->value('extras'), true))
        ->toBe([['label' => 'Müşteri', 'value' => 'Karavela A.Ş.']]);
});

test('title is required', function () {
    Livewire::test(CreateProject::class)
        ->fillForm(['title' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

test('slug must be unique', function () {
    Project::factory()->create(['slug' => 'taken']);
    $category = ProjectCategory::factory()->create();

    Livewire::test(CreateProject::class)
        ->fillForm([
            'title' => 'Another',
            'slug' => 'taken',
            'category_id' => $category->id,
            'description' => 'desc',
            'year' => 2024,
            'stack' => ['Laravel'],
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

test('category is required', function () {
    Livewire::test(CreateProject::class)
        ->fillForm(['category_id' => null])
        ->call('create')
        ->assertHasFormErrors(['category_id' => 'required']);
});

test('description is required', function () {
    Livewire::test(CreateProject::class)
        ->fillForm(['description' => null])
        ->call('create')
        ->assertHasFormErrors(['description' => 'required']);
});

test('year is required', function () {
    Livewire::test(CreateProject::class)
        ->fillForm(['year' => null])
        ->call('create')
        ->assertHasFormErrors(['year' => 'required']);
});

test('project can be edited', function () {
    $project = Project::factory()->create(['title' => 'Old']);

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($project->refresh()->title)->toBe('New');
});

test('project can be deleted from edit page', function () {
    $project = Project::factory()->create();

    Livewire::test(EditProject::class, ['record' => $project->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

test('table is reorderable by sort_order', function () {
    $a = Project::factory()->create(['sort_order' => 1]);
    $b = Project::factory()->create(['sort_order' => 2]);

    Livewire::test(ListProjects::class)
        ->call('reorderTable', [$b->getKey(), $a->getKey()]);

    expect($a->refresh()->sort_order)->toBe(2);
    expect($b->refresh()->sort_order)->toBe(1);
});
