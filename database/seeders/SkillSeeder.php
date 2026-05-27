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
                    ['name' => 'Laravel', 'description' => 'Ana çerçevem. 5 yıldır.', 'level' => 5],
                    ['name' => '.NET Core', 'description' => 'Kurumsal projeler için.', 'level' => 4],
                    ['name' => 'Node.js', 'description' => 'Hafif servisler ve real-time.', 'level' => 3],
                    ['name' => 'PHP', 'description' => 'Vanilla legacy sürdürme.', 'level' => 5],
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Frontend',
                'description' => 'Yatkın olduğum tarafta sade, erişilebilir UI.',
                'items' => [
                    ['name' => 'Vue 3', 'description' => 'Composition API + Pinia.', 'level' => 4],
                    ['name' => 'Inertia.js', 'description' => 'Laravel ile birlikte favorim.', 'level' => 5],
                    ['name' => 'React', 'description' => 'Ne zaman gerekirse.', 'level' => 3],
                    ['name' => 'Tailwind CSS', 'description' => 'Standart aracım.', 'level' => 5],
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Veritabanı',
                'description' => 'İlişkisel veri tasarımı, performans ayarları.',
                'items' => [
                    ['name' => 'PostgreSQL', 'description' => 'Yeni projelerde varsayılan.', 'level' => 5],
                    ['name' => 'MySQL', 'description' => 'Mevcut projelerde.', 'level' => 5],
                    ['name' => 'Redis', 'description' => 'Cache + queue + session.', 'level' => 4],
                    ['name' => 'MSSQL', 'description' => '.NET projeleri için.', 'level' => 3],
                ],
                'sort_order' => 3,
            ],
            [
                'name' => 'Altyapı',
                'description' => 'Production deploy ve sürdürülebilirlik.',
                'items' => [
                    ['name' => 'Docker', 'description' => 'Hemen her projede.', 'level' => 4],
                    ['name' => 'GitHub Actions', 'description' => 'CI/CD pipeline.', 'level' => 4],
                    ['name' => 'Hetzner / DO', 'description' => 'Self-managed VPS.', 'level' => 4],
                    ['name' => 'CloudFlare', 'description' => 'CDN + DNS + R2.', 'level' => 4],
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
