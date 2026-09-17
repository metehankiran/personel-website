<?php

declare(strict_types=1);

use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the stack page', function () {
    $this->get(route('stack'))
        ->assertOk()
        ->assertViewIs('pages.stack');
});

it('shows stack hero content', function () {
    $this->get(route('stack'))
        ->assertSee('Teknolojiler', escape: false)
        ->assertSee('Kullandığım teknolojiler', escape: false);
});

it('displays skill groups from database', function () {
    Skill::factory()->create([
        'name' => 'Backend',
        'description' => 'Asıl evim.',
        'items' => [
            ['name' => 'Laravel', 'description' => 'Ana çerçevem.', 'since' => 2019],
            ['name' => 'Node.js', 'description' => 'Real-time.', 'since' => null],
        ],
    ]);

    $this->get(route('stack'))
        ->assertSee('Backend', escape: false)
        ->assertSee('Asıl evim.', escape: false)
        ->assertSee('Laravel', escape: false)
        ->assertSee('Ana çerçevem.', escape: false)
        ->assertSee('Node.js', escape: false);
});

it('shows years of experience for items with a start year', function () {
    Skill::factory()->create([
        'name' => 'Test',
        'items' => [
            ['name' => 'Tool', 'description' => 'Desc', 'since' => now()->year - 7],
        ],
    ]);

    $this->get(route('stack'))
        ->assertSee('7 yıl', escape: false);
});

it('labels items started this year as new', function () {
    Skill::factory()->create([
        'name' => 'Test',
        'items' => [
            ['name' => 'Tool', 'description' => 'Desc', 'since' => now()->year],
        ],
    ]);

    $this->get(route('stack'))
        ->assertSee('Yeni', escape: false);
});

it('renders items without a start year and never shows level pips', function () {
    Skill::factory()->create([
        'name' => 'Test',
        'items' => [
            ['name' => 'Tool', 'description' => 'Desc'],
        ],
    ]);

    $this->get(route('stack'))
        ->assertOk()
        ->assertSee('Tool', escape: false)
        ->assertDontSee('yıl', escape: false)
        ->assertDontSee('w-4 h-1 rounded-sm', escape: false);
});

it('displays multiple skill groups in order', function () {
    Skill::factory()->create(['name' => 'Altyapı', 'sort_order' => 2]);
    Skill::factory()->create(['name' => 'Backend', 'sort_order' => 1]);

    $response = $this->get(route('stack'));

    $response->assertSeeInOrder(['Backend', 'Altyapı']);
});
