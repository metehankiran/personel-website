<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Sıfırdan ürün',
                'description' => 'Fikrini production-hazır bir ürüne dönüştürürüm. Backend, frontend, deployment — hepsi.',
                'features' => ['Veritabanı tasarımı', 'API + frontend', 'Auth + admin panel', 'Deployment + monitoring'],
                'pricing' => 'Starter paket',
                'duration' => '8 hafta',
                'badge' => 'En çok tercih edilen',
                'sort_order' => 1,
            ],
            [
                'title' => 'Mevcut ürüne devam',
                'description' => 'Mevcut kod tabanına geçiş veya yeni özellik ekleme, kod incelemesiyle başlar.',
                'features' => ['Kod audit + raporlama', 'Yeni özellikler', 'Refactor + performans', 'Bug fix'],
                'pricing' => 'Aylık retainer',
                'duration' => null,
                'badge' => null,
                'sort_order' => 2,
            ],
            [
                'title' => 'Teknik danışmanlık',
                'description' => 'Mimari, code review, işe alım gibi teknik kararlarda ekip desteği.',
                'features' => ['Mimari danışmanlık', 'Code review', 'Aday değerlendirme', 'Roadmap planlama'],
                'pricing' => 'Saatlik ücret',
                'duration' => null,
                'badge' => null,
                'sort_order' => 3,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
