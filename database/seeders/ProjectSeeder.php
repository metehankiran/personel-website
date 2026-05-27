<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $saas = ProjectCategory::create(['name' => 'SaaS', 'slug' => 'saas', 'sort_order' => 1]);
        $crm = ProjectCategory::create(['name' => 'CRM', 'slug' => 'crm', 'sort_order' => 2]);
        $api = ProjectCategory::create(['name' => 'API', 'slug' => 'api', 'sort_order' => 3]);
        $web = ProjectCategory::create(['name' => 'Web', 'slug' => 'web', 'sort_order' => 4]);

        $projects = [
            [
                'title' => 'Karavela',
                'slug' => 'karavela',
                'category_id' => $saas->id,
                'description' => 'Multi-tenant e-ticaret SaaS — 200+ kiracı, Laravel monolith.',
                'client' => 'Karavela Inc.',
                'year' => 2024,
                'duration' => '5 ay',
                'role' => 'Lead developer',
                'stack' => ['Laravel', 'Vue 3', 'PostgreSQL'],
                'stats' => [
                    ['label' => 'Aktif Kiracı', 'value' => '200+'],
                    ['label' => 'Aylık İstek', 'value' => '2.4M'],
                    ['label' => 'Uptime (12 ay)', 'value' => '99.97%'],
                ],
                'sort_order' => 1,
            ],
            [
                'title' => 'Flotaki',
                'slug' => 'flotaki',
                'category_id' => $crm->id,
                'description' => '.NET Core API + Vue 3 ile filo yönetim CRM\'i.',
                'client' => null,
                'year' => 2023,
                'duration' => null,
                'role' => null,
                'stack' => ['.NET Core', 'Vue 3'],
                'stats' => null,
                'sort_order' => 2,
            ],
            [
                'title' => 'Rezerv',
                'slug' => 'rezerv',
                'category_id' => $api->id,
                'description' => 'Restoran rezervasyon API + admin panel.',
                'client' => null,
                'year' => 2024,
                'duration' => null,
                'role' => null,
                'stack' => ['Laravel', 'Redis'],
                'stats' => null,
                'sort_order' => 3,
            ],
            [
                'title' => 'Atelye',
                'slug' => 'atelye',
                'category_id' => $web->id,
                'description' => 'Mimarlık ofisi için kurumsal site + headless CMS.',
                'client' => null,
                'year' => 2023,
                'duration' => null,
                'role' => null,
                'stack' => ['Nuxt', 'Sanity'],
                'stats' => null,
                'sort_order' => 4,
            ],
            [
                'title' => 'Tezgah',
                'slug' => 'tezgah',
                'category_id' => $api->id,
                'description' => 'Yerel pazaryeri için iOS + web mobil API.',
                'client' => null,
                'year' => 2022,
                'duration' => null,
                'role' => null,
                'stack' => ['Laravel', 'MySQL'],
                'stats' => null,
                'sort_order' => 5,
            ],
            [
                'title' => 'Patika',
                'slug' => 'patika',
                'category_id' => $saas->id,
                'description' => 'E-eğitim platformu, video streaming dahil.',
                'client' => null,
                'year' => 2022,
                'duration' => null,
                'role' => null,
                'stack' => ['Laravel', 'Vue', 'S3'],
                'stats' => null,
                'sort_order' => 6,
            ],
            [
                'title' => 'Kasa',
                'slug' => 'kasa',
                'category_id' => $crm->id,
                'description' => 'POS yönetim panosu, çoklu şube.',
                'client' => null,
                'year' => 2021,
                'duration' => null,
                'role' => null,
                'stack' => ['.NET Core', 'React'],
                'stats' => null,
                'sort_order' => 7,
            ],
            [
                'title' => 'Kapsül',
                'slug' => 'kapsul',
                'category_id' => $web->id,
                'description' => 'Newsletter ve içerik dağıtım aracı.',
                'client' => null,
                'year' => 2021,
                'duration' => null,
                'role' => null,
                'stack' => ['Laravel', 'Inertia'],
                'stats' => null,
                'sort_order' => 8,
            ],
            [
                'title' => 'Migros API',
                'slug' => 'migros-api',
                'category_id' => $api->id,
                'description' => 'Kurumsal stok entegrasyonu.',
                'client' => null,
                'year' => 2020,
                'duration' => null,
                'role' => null,
                'stack' => ['.NET', 'MSSQL'],
                'stats' => null,
                'sort_order' => 9,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
