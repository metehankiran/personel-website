<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The timeline used to be hardcoded in the about page. Keep that content
     * as editable default entries so the page does not go blank on deploy.
     *
     * @var array<int, array{0: string, 1: string, 2: string}>
     */
    private const array DEFAULT_ENTRIES = [
        ['2026', 'Şu an', 'Karavela v2 üzerinde çalışıyorum.'],
        ['2024', 'Karavela', 'Multi-tenant e-ticaret SaaS projesini sıfırdan inşa ettim.'],
        ['2022', 'Tam zamanlı freelance', 'Şirket bağlantımı bırakıp tamamen freelance kariyerine geçtim.'],
        ['2021', 'İlk büyük müşteri', 'Bir kurumsal CRM projesini 6 ayda teslim ettim.'],
        ['2020', 'Profesyonel başlangıç', 'Yarı zamanlı freelance işler almaya başladım.'],
        ['2018', 'Kodla tanışma', 'Lise yıllarında PHP ile başladım.'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('timeline_entries')->exists()) {
            return;
        }

        $now = now();

        DB::table('timeline_entries')->insert(array_map(
            fn (array $entry, int $index): array => [
                'period' => $entry[0],
                'title' => $entry[1],
                'description' => $entry[2],
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            self::DEFAULT_ENTRIES,
            array_keys(self::DEFAULT_ENTRIES),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('timeline_entries')->whereIn('title', array_column(self::DEFAULT_ENTRIES, 1))->delete();
    }
};
