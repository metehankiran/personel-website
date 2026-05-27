<?php

declare(strict_types=1);

use App\Enums\EducationDegree;
use App\Enums\LanguageLevel;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Language;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the cv page', function () {
    $this->get(route('cv'))
        ->assertOk()
        ->assertViewIs('pages.cv');
});

it('shows cv hero content', function () {
    $this->get(route('cv'))
        ->assertSee('CV', escape: false)
        ->assertSee('Metehan Kıran', escape: false);
});

it('displays experiences from database', function () {
    Experience::factory()->create([
        'title' => 'Senior Developer',
        'company' => 'Acme Inc.',
        'start_date' => '2020-01-01',
        'end_date' => null,
    ]);

    $this->get(route('cv'))
        ->assertSee('Senior Developer', escape: false)
        ->assertSee('Acme Inc.', escape: false)
        ->assertSee('2020', escape: false)
        ->assertSee('bugün', escape: false);
});

it('displays skills from database', function () {
    Skill::factory()->create([
        'name' => 'Backend',
        'items' => [
            ['name' => 'Laravel', 'description' => 'Ana çerçevem.', 'level' => 5],
            ['name' => 'PHP', 'description' => 'Legacy.', 'level' => 5],
        ],
    ]);

    $this->get(route('cv'))
        ->assertSee('Backend', escape: false)
        ->assertSee('Laravel · PHP', escape: false);
});

it('displays education from database', function () {
    Education::factory()->create([
        'school' => 'İTÜ',
        'degree' => EducationDegree::Bachelor,
        'field' => 'Bilgisayar Mühendisliği',
        'start_date' => '2017-09-01',
        'end_date' => '2021-06-01',
    ]);

    $this->get(route('cv'))
        ->assertSee('Bilgisayar Mühendisliği', escape: false)
        ->assertSee('Lisans', escape: false)
        ->assertSee('İTÜ', escape: false);
});

it('displays languages from database', function () {
    Language::factory()->create(['name' => 'Türkçe', 'level' => LanguageLevel::Native]);
    Language::factory()->create(['name' => 'İngilizce', 'level' => LanguageLevel::Advanced]);

    $this->get(route('cv'))
        ->assertSee('Türkçe', escape: false)
        ->assertSee('Ana Dil', escape: false)
        ->assertSee('İngilizce', escape: false)
        ->assertSee('İleri', escape: false);
});
