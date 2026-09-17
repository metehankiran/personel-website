<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults are the copy that used to be hardcoded in the about page, so
     * the page reads the same after deploy until it is edited in the panel.
     */
    public function up(): void
    {
        $this->migrator->add('about.heading', '5 yıldır kod yazıyor, ürün teslim ediyorum.');
        $this->migrator->add('about.body', '<p>Lise yıllarında PHP ile başlayan kod yolculuğum, bugün Laravel, Vue.js ve .NET Core ekosistemlerinde derinleşmiş bir pratiğe dönüştü. 5 yıldır freelance olarak çalışıyorum.</p><p>E-ticaret altyapılarından kurumsal CRM\'lere, dahili yönetim araçlarından mobil uygulama backend\'lerine kadar geniş bir yelpazede proje teslim ettim. 40\'tan fazla müşteriyle çalıştım — bazılarıyla hâlâ çalışmaya devam ediyorum.</p><p>İyi yazılım benim için <em>fark edilmeyen</em> yazılımdır: kullanıcı düşünmeden iş gören, bakımı kolay, gelecek versiyonlara dirençli kod. O yüzden modaya kapılmadan, doğrulanmış araçlarla çalışmayı tercih ediyorum.</p><p>Kodun dışında: kitap okumayı, uzun yürüyüşleri ve mekanik klavyeleri seviyorum.</p>');
        $this->migrator->add('about.portrait_path', null);
        $this->migrator->add('about.work_mode', 'Uzaktan');
    }

    public function down(): void
    {
        $this->migrator->deleteIfExists('about.heading');
        $this->migrator->deleteIfExists('about.body');
        $this->migrator->deleteIfExists('about.portrait_path');
        $this->migrator->deleteIfExists('about.work_mode');
    }
};
