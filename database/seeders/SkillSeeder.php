<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            [
                'name' => 'Backend',
                'description' => 'Asıl evim. Karmaşık iş mantığı, veri modelleme, API tasarımı.',
                'items' => [
                    ['name' => 'Laravel', 'description' => 'Ana çerçevem. 5 yıldır.', 'since' => 2019],
                    ['name' => '.NET Core', 'description' => 'Kurumsal projeler için.', 'since' => 2020],
                    ['name' => 'Node.js', 'description' => 'Hafif servisler ve real-time.', 'since' => 2021],
                    ['name' => 'PHP', 'description' => 'Vanilla legacy sürdürme.', 'since' => 2017],
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Frontend',
                'description' => 'Yatkın olduğum tarafta sade, erişilebilir UI.',
                'items' => [
                    ['name' => 'Vue 3', 'description' => 'Composition API + Pinia.', 'since' => 2020],
                    ['name' => 'Inertia.js', 'description' => 'Laravel ile birlikte favorim.', 'since' => 2021],
                    ['name' => 'React', 'description' => 'Ne zaman gerekirse.', 'since' => 2022],
                    ['name' => 'Tailwind CSS', 'description' => 'Standart aracım.', 'since' => 2020],
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Veritabanı',
                'description' => 'İlişkisel veri tasarımı, performans ayarları.',
                'items' => [
                    ['name' => 'PostgreSQL', 'description' => 'Yeni projelerde varsayılan.', 'since' => 2020],
                    ['name' => 'MySQL', 'description' => 'Mevcut projelerde.', 'since' => 2017],
                    ['name' => 'Redis', 'description' => 'Cache + queue + session.', 'since' => 2020],
                    ['name' => 'MSSQL', 'description' => '.NET projeleri için.', 'since' => 2020],
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Altyapı',
                'description' => 'Production deploy ve sürdürülebilirlik.',
                'items' => [
                    ['name' => 'Docker', 'description' => 'Hemen her projede.', 'since' => 2020],
                    ['name' => 'GitHub Actions', 'description' => 'CI/CD pipeline.', 'since' => 2021],
                    ['name' => 'Hetzner / DO', 'description' => 'Self-managed VPS.', 'since' => 2019],
                    ['name' => 'CloudFlare', 'description' => 'CDN + DNS + R2.', 'since' => 2020],
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
