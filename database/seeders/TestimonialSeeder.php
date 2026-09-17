<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Support\ImageGenerator;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Selin Akın', 'rating' => 5, 'title' => 'Co-founder', 'company' => 'Karavela', 'body' => 'Onunla çalışmak bir takım arkadaşıyla çalışmak gibi. Sadece kod değil, ürün düşüncesi de katıyor.'],
            ['name' => 'Mert Yıldız', 'rating' => 5, 'title' => 'CTO', 'company' => 'Flotaki', 'body' => 'Tahminleri güvenilir. Bir freelancer için bu büyük bir avantaj.'],
            ['name' => 'Ayşe Demirci', 'rating' => 5, 'title' => 'Engineering Lead', 'company' => null, 'body' => 'Kod kalitesi senior seviyesindeki mühendislerimizle eşleşiyor. Devam projesini ona verdik.'],
            ['name' => 'Burak Çelik', 'rating' => 4, 'title' => 'Founder', 'company' => 'Rezerv', 'body' => '6 aylık projeyi 5 ayda teslim etti. Hâlâ production\'da sorunsuz çalışıyor.'],
            ['name' => 'Deniz Karaca', 'title' => 'Product Manager', 'company' => null, 'body' => 'Açık iletişim ve haftalık demolar çok değerliydi. Süreç boyunca hiç sürpriz yaşamadık.'],
            ['name' => 'Emre Yalçın', 'rating' => 5, 'title' => 'CTO', 'company' => 'Tezgah', 'body' => 'Refactor\'ı tahmini 6 ay yerine 5 ayda tamamladı. %40 performans artışı sağladı.'],
            ['name' => 'Gizem Tan', 'title' => 'Founder', 'company' => 'Atelye', 'body' => 'Teknik implementasyonun yanında iş mantığını da anlıyor. Önerileri her zaman değerli.'],
        ];

        foreach ($testimonials as $i => $data) {
            $initials = collect(explode(' ', $data['name']))->map(fn ($w) => mb_substr($w, 0, 1))->join('');

            Testimonial::create([
                ...$data,
                'avatar' => ImageGenerator::avatar($initials),
                'sort_order' => $i + 1,
            ]);
        }
    }
}
