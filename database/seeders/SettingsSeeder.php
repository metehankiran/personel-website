<?php

namespace Database\Seeders;

use App\Settings\GeneralSettings;
use App\Settings\SeoSettings;
use App\Settings\SocialSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $appName = config('app.name', 'Personel Website');
        $appSlug = Str::slug($appName);

        $general = app(GeneralSettings::class);
        $general->site_title = $appName;
        $general->site_description = 'Bağımsız full-stack developer. Laravel, Vue.js ve .NET Core.';
        $general->author_name = $appName;
        $general->author_email = "hello@{$appSlug}.com";
        $general->author_phone = '+90 555 000 00 00';
        $general->author_address = 'İstanbul, Türkiye';
        $general->logo_path = null;
        $general->favicon_path = null;
        $general->cv_path = null;
        $general->author_title = 'Full-stack Developer';
        $general->author_location = 'İstanbul, TR';
        $general->bio = '5 yıllık deneyime sahip bağımsız full-stack developer. Laravel, Vue.js ve .NET Core ekosistemlerinde uzmanlaştım. 40+ müşteriyle — KOBİ\'lerden kurumsal şirketlere — production-hazır ürünler teslim ettim.';
        $general->hero_title = 'Bağımsız <em>full-stack</em> developer.<br/><span class="dim">Ürünleri sıfırdan teslim ederim.</span>';
        $general->hero_subtitle = '5 yıldır Laravel, Vue ve .NET Core ile çalışıyorum. E-ticaretten kurumsal CRM\'lere — küçük ekiplerle ya da tek başıma.';
        $general->availability_status = 'Yeni proje alıyor';
        $general->homepage_stats = [
            ['label' => 'Tecrübe', 'value' => '5+ yıl'],
            ['label' => 'Lokasyon', 'value' => 'İstanbul, TR'],
            ['label' => 'Müsaitlik', 'value' => 'Ocak 2026'],
            ['label' => 'Çalıştığım', 'value' => '40+ müşteri'],
        ];
        $general->footer_text = 'Bağımsız full-stack developer. Laravel, Vue ve .NET Core ile ürünler inşa ediyorum. İstanbul\'dan, dünyanın her yerine.';
        $general->kvkk_page_slug = 'kvkk';
        $general->cookie_policy_slug = 'cookie-policy';
        $general->google_maps_url = null;
        $general->save();

        $seo = app(SeoSettings::class);
        $seo->meta_description = "{$appName} — Bağımsız full-stack developer. Laravel, Vue.js, .NET Core ile production-hazır ürünler.";
        $seo->og_image_path = null;
        $seo->google_analytics_id = 'G-XXXXXXXXXX';
        $seo->google_search_console_id = null;
        $seo->save();

        $social = app(SocialSettings::class);
        $social->github_url = 'https://github.com';
        $social->linkedin_url = 'https://linkedin.com';
        $social->twitter_url = 'https://x.com';
        $social->youtube_url = null;
        $social->instagram_url = 'https://instagram.com';
        $social->bluesky_url = null;
        $social->save();
    }
}
