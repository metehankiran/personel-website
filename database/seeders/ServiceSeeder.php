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
                'pricing' => 'Proje bitimi',
                'duration' => '8 hafta',
                'badge' => 'En çok tercih edilen',
                'sort_order' => 1,
            ],
            [
                'title' => 'Mevcut ürüne devam',
                'description' => 'Mevcut kod tabanına geçiş veya yeni özellik ekleme, kod incelemesiyle başlar.',
                'features' => ['Kod audit + raporlama', 'Yeni özellikler', 'Refactor + performans', 'Bug fix'],
                'pricing' => 'Aylık',
                'duration' => null,
                'badge' => null,
                'sort_order' => 2,
            ],
            [
                'title' => 'Teknik danışmanlık',
                'description' => 'Mimari, code review, işe alım gibi teknik kararlarda ekip desteği.',
                'features' => ['Mimari danışmanlık', 'Code review', 'Aday değerlendirme', 'Roadmap planlama'],
                'pricing' => 'Saatlik',
                'duration' => null,
                'badge' => null,
                'sort_order' => 3,
            ],
            [
                'title' => 'API Geliştirme',
                'description' => 'Mobil uygulama veya üçüncü parti entegrasyonlar için RESTful API tasarım ve geliştirme.',
                'features' => ['REST API tasarımı', 'Üçüncü parti entegrasyon', 'API dokümantasyonu', 'Rate limiting + auth'],
                'pricing' => 'Proje bazlı',
                'duration' => '4 hafta',
                'badge' => null,
                'sort_order' => 4,
            ],
            [
                'title' => 'DevOps & Altyapı',
                'description' => 'Uygulamanı production\'a taşırım. CI/CD, container, monitoring — hepsi dahil.',
                'features' => ['Docker + CI/CD pipeline', 'Sunucu kurulum + hardening', 'Zero-downtime deployment', 'Monitoring + alerting'],
                'pricing' => 'Proje bazlı',
                'duration' => '1 hafta',
                'badge' => null,
                'sort_order' => 5,
            ],
            [
                'title' => 'SEO & Performans',
                'description' => 'Sitenin arama motorlarında görünürlüğünü ve teknik performansını artırırım.',
                'features' => ['Teknik SEO audit', 'Core Web Vitals optimizasyonu', 'Yapılandırılmış veri (Schema.org)', 'Sayfa hızı iyileştirme'],
                'pricing' => 'Proje bazlı',
                'duration' => '2 hafta',
                'badge' => null,
                'sort_order' => 6,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
