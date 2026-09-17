<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Subjects used to be enum values; they are now stored as the label the
     * visitor saw, so old messages stay readable without the enum.
     *
     * @var array<string, string>
     */
    private const array LEGACY_LABELS = [
        'project_inquiry' => 'Proje Teklifi',
        'collaboration' => 'İş Birliği',
        'consulting' => 'Danışmanlık',
        'support' => 'Teknik Destek',
        'feedback' => 'Geri Bildirim',
        'other' => 'Diğer',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::LEGACY_LABELS as $value => $label) {
            DB::table('contacts')->where('subject', $value)->update(['subject' => $label]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (self::LEGACY_LABELS as $value => $label) {
            DB::table('contacts')->where('subject', $label)->whereNull('service_id')->update(['subject' => $value]);
        }
    }
};
