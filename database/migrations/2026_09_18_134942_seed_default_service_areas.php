<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    private const string PROVINCE = 'Kütahya';

    /**
     * Deploys only run migrations, so the first areas ship here as editable defaults:
     * Kütahya and each of its districts, written around what that district lives on.
     *
     * @var array<int, array{name: string, summary: string, description: string, sectors: array<int, string>}>
     */
    private const array DEFAULT_AREAS = [
        [
            'name' => 'Kütahya Merkez',
            'summary' => 'Çini, seramik ve porselenin başkentinde; atölyeden fabrikaya, esnaftan üniversite çevresine web sitesi ve yazılım.',
            'description' => "Kütahya denince akla gelen çini, seramik ve porselen, internette hâlâ hak ettiği kadar görünmüyor. Çini atölyeleri ve el sanatları dükkânları için ürünleri tek tek anlatan, yurt içine ve yurt dışına satış yapabilen e-ticaret siteleri kuruyorum.\nOrganize sanayi bölgesindeki üreticiler için kurumsal web sitesi, ürün kataloğu ve teklif toplama formları; iki üniversitenin çevresindeki kafe, yurt, kurs ve klinikler için Google'da ve haritalarda bulunabilen hızlı siteler hazırlıyorum. Kütahya'da yaşadığım için yüz yüze görüşmek ve işletmeyi yerinde görmek kolay.",
            'sectors' => ['Çini ve seramik atölyeleri', 'Porselen ve sanayi üreticileri', 'Klinik, kurs ve danışmanlık ofisleri', 'Kafe, restoran ve konaklama'],
        ],
        [
            'name' => 'Tavşanlı',
            'summary' => 'İlin en büyük ilçesinde sanayi, madencilik tedarikçileri ve çarşı esnafı için kurumsal site ve e-ticaret.',
            'description' => "Tavşanlı, linyit havzası ve sanayisiyle Kütahya'nın en hareketli ilçesi. Madenciliğe ve enerjiye hizmet veren tedarikçi firmalar için referanslarını, makine parkını ve belgelerini düzgün anlatan kurumsal web siteleri hazırlıyorum; ihale ve teklif süreçlerinde ilk bakılan yer artık firmanın sitesi.\nTavşanlı leblebisi gibi yöresel ürünleri şehir dışına satmak isteyen üreticiler için ödeme ve kargo entegrasyonlu e-ticaret siteleri, çarşıdaki işletmeler için de Google'da ilçe aramalarında çıkan sade ve hızlı siteler kuruyorum.",
            'sectors' => ['Maden ve enerji tedarikçileri', 'Sanayi ve imalat firmaları', 'Leblebi ve yöresel ürün üreticileri', 'Çarşı esnafı ve mağazalar'],
        ],
        [
            'name' => 'Simav',
            'summary' => 'Termal turizm ve jeotermal seracılığın ilçesinde otel, apart ve üreticiler için rezervasyon odaklı siteler.',
            'description' => "Simav'ı Eynal kaplıcaları ve termal turizm taşıyor. Termal otel, apart ve pansiyonlar için oda ve fiyat bilgisini net gösteren, telefonda hızlı açılan ve doğrudan rezervasyon talebi alan web siteleri kuruyorum; böylece her rezervasyonda aracı sitelere komisyon ödemek zorunda kalmazsınız.\nJeotermal seralarda üretim yapan işletmeler için toptan alıcıya hitap eden kurumsal siteler, ilçedeki sağlık ve fizik tedavi merkezleri için de randevu talebi toplayan sayfalar hazırlıyorum.",
            'sectors' => ['Termal otel, apart ve pansiyonlar', 'Jeotermal seracılık', 'Sağlık ve fizik tedavi merkezleri', 'Yerel esnaf ve restoranlar'],
        ],
        [
            'name' => 'Gediz',
            'summary' => 'Murat Dağı, kaplıcalar ve organize sanayi: turizm işletmeleri ve üreticiler için web sitesi ve yazılım.',
            'description' => "Gediz'in iki güçlü yanı var: Murat Dağı'ndaki termal ve kış turizmi ile organize sanayi bölgesi. Dağdaki ve Ilıca'daki konaklama tesisleri için sezonluk kampanyaları kolayca güncelleyebileceğiniz, rezervasyon talebi alan siteler kuruyorum.\nSanayideki üreticiler için ürün kataloğu, bayi ve teklif formları içeren kurumsal siteler; Gediz tarhanası gibi yöresel ürünleri paketleyip satan işletmeler için de e-ticaret altyapısı hazırlıyorum.",
            'sectors' => ['Termal ve kış turizmi tesisleri', 'OSB üreticileri', 'Tarhana ve yöresel gıda', 'Yerel esnaf'],
        ],
        [
            'name' => 'Emet',
            'summary' => 'Bor madenciliği ve kaplıcalarıyla bilinen ilçede tedarikçi firmalar ve termal tesisler için siteler.',
            'description' => "Emet, bor yatakları ve şifalı kaplıcalarıyla tanınıyor. Madene hizmet veren nakliye, bakım ve tedarik firmaları için yetkinliklerini, araç ve ekipman parkını ve iş güvenliği belgelerini öne çıkaran kurumsal web siteleri hazırlıyorum.\nTermal otel ve pansiyonlar için de kaplıcayı arayan misafirin Google'da doğrudan işletmeyi bulmasını sağlayan, rezervasyon talebi toplayan hızlı siteler kuruyorum.",
            'sectors' => ['Maden tedarikçileri ve nakliye', 'Termal otel ve pansiyonlar', 'Yerel esnaf'],
        ],
        [
            'name' => 'Şaphane',
            'summary' => 'Adını şap madeninden alan küçük ilçede üreticiler ve esnaf için sade, bakımı kolay web siteleri.',
            'description' => "Şaphane küçük bir ilçe; tam da bu yüzden internette görünür olan ilk birkaç işletme aramaların neredeyse tamamını alıyor. Vişne ve meyve üreticileri, kooperatifler ve yerel işletmeler için kendi başınıza güncelleyebileceğiniz, aylık masrafı düşük, sade web siteleri kuruyorum.\nÜrününü ilçe dışına satmak isteyenler için sipariş formu ya da küçük ölçekli e-ticaret ile başlayıp, iş büyüdükçe genişleyen bir yapı öneriyorum.",
            'sectors' => ['Vişne ve meyve üreticileri', 'Kooperatifler', 'Yerel esnaf'],
        ],
        [
            'name' => 'Altıntaş',
            'summary' => 'Zafer Havalimanı\'nın ilçesinde mermer, tarım ve ulaşım işletmeleri için kurumsal web siteleri.',
            'description' => "Altıntaş, Zafer Havalimanı'na ev sahipliği yapıyor ve mermer ocaklarıyla biliniyor. Mermer ve doğal taş firmaları için ürünlerini yüksek kaliteli görsellerle sunan, yurt dışı alıcıya da hitap edebilen çok dilli kurumsal siteler hazırlıyorum.\nHavalimanı transferi, araç kiralama ve konaklama işletmeleri için uçuş arayan yolcunun karşısına çıkan siteler; tarım işletmeleri ve kooperatifler için de tanıtım ve sipariş sayfaları kuruyorum.",
            'sectors' => ['Mermer ve doğal taş', 'Transfer, araç kiralama ve konaklama', 'Tarım işletmeleri ve kooperatifler'],
        ],
        [
            'name' => 'Domaniç',
            'summary' => 'Ormanları ve yaylalarıyla bilinen ilçede orman ürünleri, yayla turizmi ve yöresel üreticiler için siteler.',
            'description' => "Domaniç, ormanları, yaylaları ve Hayme Ana'nın hatırasıyla Kütahya'nın en yeşil ilçesi. Kereste, orman ürünleri ve mobilya atölyeleri için ürün ve ölçü bilgisini düzgün sunan, teklif toplayan kurumsal siteler hazırlıyorum.\nYayla evi, bungalov ve kamp işletmeleri için fotoğrafları öne çıkaran, müsaitlik sorup rezervasyon talebi alan siteler; bal ve yöresel ürün satanlar için de basit bir e-ticaret altyapısı kuruyorum.",
            'sectors' => ['Kereste, orman ürünleri ve mobilya', 'Yayla evi, bungalov ve kamp', 'Bal ve yöresel ürünler'],
        ],
        [
            'name' => 'Hisarcık',
            'summary' => 'Bor madenciliği ve tarımla geçinen ilçede tedarikçiler, üreticiler ve esnaf için web siteleri.',
            'description' => "Hisarcık'ta ekonomi bor madenciliği ile tarım etrafında dönüyor. Madene hizmet veren yerel firmalar için yeterliliklerini ve referanslarını anlatan kurumsal web siteleri hazırlıyorum.\nTarım üreticileri ve ilçe esnafı için de Google'da ve haritalarda bulunmayı sağlayan, telefonla ya da WhatsApp'tan tek dokunuşla ulaşılabilen sade siteler kuruyorum.",
            'sectors' => ['Maden tedarikçileri', 'Tarım üreticileri', 'Yerel esnaf'],
        ],
        [
            'name' => 'Aslanapa',
            'summary' => 'Merkeze komşu, tarım ve hayvancılık ilçesinde üreticiler ve küçük işletmeler için ilk web sitesi.',
            'description' => "Aslanapa, Kütahya merkeze yakınlığı sayesinde yüz yüze çalışmanın en kolay olduğu ilçelerden. Tarım ve hayvancılık işletmeleri, süt ve yem üreticileri için ürünlerini ve iletişim bilgilerini derli toplu sunan tanıtım siteleri hazırlıyorum.\nİlk kez web sitesi yaptıracak işletmeler için alan adı, e-posta, Google İşletme Profili ve site kurulumunu tek pakette, anlaşılır bir dille hallediyorum.",
            'sectors' => ['Tarım ve hayvancılık işletmeleri', 'Süt ve yem üreticileri', 'Yerel esnaf'],
        ],
        [
            'name' => 'Çavdarhisar',
            'summary' => 'Aizanoi Antik Kenti ve Zeus Tapınağı\'nın ilçesinde turizm işletmeleri için çok dilli web siteleri.',
            'description' => "Çavdarhisar, Aizanoi Antik Kenti'ne ve Anadolu'nun en iyi korunmuş Zeus tapınaklarından birine ev sahipliği yapıyor. Ziyaretçi ilçeye gelmeden önce nerede kalacağını, ne yiyeceğini ve nasıl ulaşacağını internetten arıyor.\nPansiyon, restoran, rehberlik ve hediyelik eşya işletmeleri için Türkçe ve İngilizce, harita ve yol tarifiyle birlikte çalışan, telefonda hızlı açılan siteler kuruyorum.",
            'sectors' => ['Pansiyon ve konaklama', 'Restoran ve kafeler', 'Rehberlik ve tur hizmetleri', 'Hediyelik ve el sanatları'],
        ],
        [
            'name' => 'Dumlupınar',
            'summary' => 'Büyük Taarruz\'un tarihi ilçesinde ziyaretçiye hizmet veren işletmeler ve üreticiler için siteler.',
            'description' => "Dumlupınar, Büyük Taarruz'un ve Başkomutanlık Meydan Muharebesi'nin geçtiği topraklar; her yıl 30 Ağustos'ta ve yıl boyunca binlerce ziyaretçi ağırlıyor. Ziyaretçiye hizmet veren konaklama, yeme içme ve tur işletmeleri için tören dönemlerinde öne çıkan, yol tarifi ve iletişim bilgisi net web siteleri hazırlıyorum.\nİlçedeki tarım üreticileri ve esnaf için de bakımı kolay, düşük maliyetli tanıtım siteleri kuruyorum.",
            'sectors' => ['Konaklama ve yeme içme', 'Tur ve ziyaretçi hizmetleri', 'Tarım üreticileri'],
        ],
        [
            'name' => 'Pazarlar',
            'summary' => 'Kiraz ve vişnesiyle bilinen ilçede meyve üreticileri ve kooperatifler için tanıtım ve sipariş siteleri.',
            'description' => "Pazarlar, Kütahya'nın en küçük ilçelerinden biri ama kirazı ve vişnesiyle adını duyuruyor. Meyve üreticileri ve kooperatifler için hasat döneminde toptan alıcıya ulaşmayı kolaylaştıran, ürün ve iletişim bilgisini öne çıkaran siteler hazırlıyorum.\nDoğrudan tüketiciye satmak isteyenler için ön sipariş formu ile başlayan, talep arttıkça e-ticarete dönüşebilen esnek bir yapı kuruyorum.",
            'sectors' => ['Kiraz ve vişne üreticileri', 'Kooperatifler', 'Yerel esnaf'],
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::table('service_areas')->exists()) {
            return;
        }

        $now = now();

        DB::table('service_areas')->insert(array_map(
            fn (array $area, int $index): array => [
                'name' => $area['name'],
                'slug' => Str::slug($area['name']),
                'province' => self::PROVINCE,
                'summary' => $area['summary'],
                'description' => $area['description'],
                'sectors' => json_encode($area['sectors'], JSON_UNESCAPED_UNICODE),
                'is_published' => true,
                'sort_order' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            self::DEFAULT_AREAS,
            array_keys(self::DEFAULT_AREAS),
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_areas')->whereIn('name', array_column(self::DEFAULT_AREAS, 'name'))->delete();
    }
};
