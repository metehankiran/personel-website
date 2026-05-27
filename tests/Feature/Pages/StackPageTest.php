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
        ->assertSee('Stack', escape: false)
        ->assertSee('Kullandığım teknolojiler', escape: false);
});

it('displays skill groups from database', function () {
    Skill::factory()->create([
        'name' => 'Backend',
        'description' => 'Asıl evim.',
        'items' => [
            ['name' => 'Laravel', 'description' => 'Ana çerçevem.', 'level' => 5],
            ['name' => 'Node.js', 'description' => 'Real-time.', 'level' => 3],
        ],
    ]);

    $this->get(route('stack'))
        ->assertSee('Backend', escape: false)
        ->assertSee('Asıl evim.', escape: false)
        ->assertSee('Laravel', escape: false)
        ->assertSee('Ana çerçevem.', escape: false)
        ->assertSee('Node.js', escape: false);
});

it('displays skill level pips correctly', function () {
    Skill::factory()->create([
        'name' => 'Test',
        'items' => [
            ['name' => 'Tool', 'description' => 'Desc', 'level' => 3],
        ],
    ]);

    $response = $this->get(route('stack'));

    $response->assertSee('stack-pip on', escape: false);
});

it('displays multiple skill groups in order', function () {
    Skill::factory()->create(['name' => 'Altyapı', 'sort_order' => 2]);
    Skill::factory()->create(['name' => 'Backend', 'sort_order' => 1]);

    $response = $this->get(route('stack'));

    $response->assertSeeInOrder(['Backend', 'Altyapı']);
});
