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
            // Status publikasi untuk menangani LoA vs sudah terbit
            $table->enum('publication_status', ['draft', 'submitted', 'accepted', 'published'])->default('draft')->after('is_published');
            
            // Field untuk LoA (Letter of Acceptance)
            $table->string('loa_file_path')->nullable()->after('publication_status');
            $table->date('loa_date')->nullable()->after('loa_file_path');
            $table->string('loa_number')->nullable()->after('loa_date');
            
            // Field untuk tracking progress penerbitan
            $table->date('submission_date_to_publisher')->nullable()->after('loa_number');
            $table->date('expected_publication_date')->nullable()->after('submission_date_to_publisher');
            $table->text('publication_notes')->nullable()->after('expected_publication_date');
            
            // Field untuk ISBN sementara (jika sudah ada)
            $table->string('expected_isbn')->nullable()->after('publication_notes');
            $table->string('expected_doi')->nullable()->after('expected_isbn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'publication_status',
                'loa_file_path',
                'loa_date',
                'loa_number',
                'submission_date_to_publisher',
                'expected_publication_date',
                'publication_notes',
                'expected_isbn',
                'expected_doi'
            ]);
        });
    }
};
