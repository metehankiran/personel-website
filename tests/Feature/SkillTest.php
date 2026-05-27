<?php

use App\Models\Skill;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('skill can be created with valid attributes', function () {
    $skill = Skill::factory()->create();

    expect($skill)->toBeInstanceOf(Skill::class)
        ->and($skill->name)->toBeString()
        ->and($skill->items)->toBeArray();
});

test('skill items are stored as json array', function () {
    $skill = Skill::factory()->create([
        'name' => 'Backend',
        'items' => ['PHP', '.NET Core', 'Node.js'],
    ]);

    $fresh = $skill->fresh();

    expect($fresh->items)->toBe(['PHP', '.NET Core', 'Node.js']);
});

test('skills are ordered by sort_order', function () {
    Skill::factory()->create(['sort_order' => 3, 'name' => 'Third']);
    Skill::factory()->create(['sort_order' => 1, 'name' => 'First']);
    Skill::factory()->create(['sort_order' => 2, 'name' => 'Second']);

    $skills = Skill::ordered()->get();

    expect($skills->pluck('name')->toArray())
        ->toBe(['First', 'Second', 'Third']);
});

test('skill seeder runs without errors', function () {
    $this->seed(SkillSeeder::class);

    expect(Skill::count())->toBeGreaterThan(0);
});
