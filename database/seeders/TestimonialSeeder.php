<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Selin Akın',
                'title' => 'Co-founder',
                'company' => 'Karavela',
                'body' => 'Metehan ile çalışmak bir takım arkadaşıyla çalışmak gibi. Sadece kod değil, ürün düşüncesi de katıyor.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Mert Yıldız',
                'title' => 'CTO',
                'company' => 'Flotaki',
                'body' => 'Tahminleri güvenilir. Bir freelancer için bu büyük bir avantaj.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Ayşe Demirci',
                'title' => 'Engineering Lead',
                'company' => null,
                'body' => 'Kod kalitesi senior seviyesindeki mühendislerimizle eşleşiyor. Devam projesini ona verdik.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Burak Çelik',
                'title' => 'Founder',
                'company' => 'Rezerv',
                'body' => '6 aylık projeyi 5 ayda teslim etti. Hâlâ production\'da sorunsuz çalışıyor.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Deniz Karaca',
                'title' => 'Product Manager',
                'company' => null,
                'body' => 'Açık iletişim ve haftalık demolar çok değerliydi. Süreç boyunca hiç sürpriz yaşamadık.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Emre Yalçın',
                'title' => 'CTO',
                'company' => 'Tezgah',
                'body' => 'Refactor\'ı tahmini 6 ay yerine 5 ayda tamamladı. %40 performans artışı sağladı.',
                'sort_order' => 6,
            ],
            [
                'name' => 'Gizem Tan',
                'title' => 'Founder',
                'company' => 'Atelye',
                'body' => 'Teknik implementasyonun yanında iş mantığını da anlıyor. Önerileri her zaman değerli.',
                'sort_order' => 7,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
