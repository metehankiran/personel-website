<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pages are only public once they have a publish date. Pages that were
     * already switched on keep working by taking their creation time.
     */
    public function up(): void
    {
        DB::table('pages')
            ->where('is_published', true)
            ->whereNull('published_at')
            ->update(['published_at' => DB::raw('created_at')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The publish dates are harmless to keep; the column itself is dropped by the previous migration.
    }
};
