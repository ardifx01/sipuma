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
            // Hapus field yang tidak masuk akal
            $table->dropColumn(['expected_isbn', 'expected_doi']);
            
            // Tambah field yang lebih masuk akal
            $table->string('publisher_name')->nullable()->after('expected_publication_date');
            $table->string('journal_name_expected')->nullable()->after('publisher_name');
            $table->text('publication_agreement_notes')->nullable()->after('journal_name_expected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->string('expected_isbn')->nullable();
            $table->string('expected_doi')->nullable();
            $table->dropColumn(['publisher_name', 'journal_name_expected', 'publication_agreement_notes']);
        });
    }
};
