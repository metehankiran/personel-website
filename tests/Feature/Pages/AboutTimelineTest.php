<?php

declare(strict_types=1);

use App\Models\TimelineEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('ships the previously hardcoded timeline as default entries', function () {
    expect(TimelineEntry::ordered()->pluck('title')->all())->toBe([
        'Şu an', 'Karavela', 'Tam zamanlı freelance', 'İlk büyük müşteri', 'Profesyonel başlangıç', 'Kodla tanışma',
    ]);
});

it('does not add the defaults again when entries already exist', function () {
    TimelineEntry::query()->delete();
    TimelineEntry::factory()->create(['title' => 'Benim kaydım']);

    $migration = require collect(glob(database_path('migrations/*_seed_default_timeline_entries.php')))->sole();
    $migration->up();

    expect(DB::table('timeline_entries')->pluck('title')->all())->toBe(['Benim kaydım']);
});

it('renders the timeline from the database in the configured order', function () {
    TimelineEntry::query()->delete();
    TimelineEntry::factory()->create(['period' => '2019', 'title' => 'Eski iş', 'description' => 'İlk ekibim.', 'sort_order' => 2]);
    TimelineEntry::factory()->create(['period' => 'Şimdi', 'title' => 'Yeni iş', 'description' => 'Kendi ürünüm.', 'sort_order' => 1]);

    $this->get(route('about'))
        ->assertOk()
        ->assertSeeInOrder(['Zaman çizelgesi', 'Şimdi', 'Yeni iş', 'Kendi ürünüm.', '2019', 'Eski iş', 'İlk ekibim.']);
});

it('no longer prints hardcoded timeline rows', function () {
    TimelineEntry::query()->delete();
    TimelineEntry::factory()->create(['title' => 'Tek kayıt']);

    $this->get(route('about'))
        ->assertSee('Tek kayıt')
        ->assertDontSee('İlk büyük müşteri')
        ->assertDontSee('Kodla tanışma');
});

it('hides the timeline section when there are no entries', function () {
    TimelineEntry::query()->delete();

    $this->get(route('about'))->assertOk()->assertDontSee('Zaman çizelgesi');
});

it('renders entries without a description', function () {
    TimelineEntry::query()->delete();
    TimelineEntry::factory()->create(['title' => 'Açıklamasız', 'description' => null]);

    $this->get(route('about'))->assertOk()->assertSee('Açıklamasız');
});
