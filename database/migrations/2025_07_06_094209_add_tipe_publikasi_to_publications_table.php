<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            // Tambahkan kolom sumber_artikel terlebih dahulu
            $table->enum('sumber_artikel', ['Skripsi', 'Magang', 'Riset'])->nullable()->after('pages');
            // Kemudian tambahkan tipe_publikasi
            $table->json('tipe_publikasi')->nullable()->after('sumber_artikel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn(['sumber_artikel', 'tipe_publikasi']);
        });
    }
};
