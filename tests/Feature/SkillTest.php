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

test('skill items store structured data with level', function () {
    $items = [
        ['name' => 'Laravel', 'description' => 'Ana çerçevem.', 'level' => 5],
        ['name' => 'Node.js', 'description' => 'Real-time.', 'level' => 3],
    ];

    $skill = Skill::factory()->create(['name' => 'Backend', 'items' => $items]);

    $fresh = $skill->fresh();

    // MySQL JSON columns do not preserve object key order, so compare loosely.
    expect($fresh->items)->toEqual($items)
        ->and($fresh->items[0]['level'])->toBe(5);
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
