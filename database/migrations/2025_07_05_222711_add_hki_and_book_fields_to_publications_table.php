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
            // HKI fields
            $table->date('hki_publication_date')->nullable();
            $table->string('hki_creator')->nullable();
            $table->string('hki_certificate')->nullable(); // file path
            
            // Book fields
            $table->string('book_title')->nullable();
            $table->string('book_publisher')->nullable();
            $table->integer('book_year')->nullable();
            $table->string('book_edition')->nullable();
            $table->string('book_editor')->nullable();
            $table->string('book_isbn')->nullable();
            $table->string('book_pdf')->nullable(); // file path
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'hki_publication_date',
                'hki_creator',
                'hki_certificate',
                'book_title',
                'book_publisher',
                'book_year',
                'book_edition',
                'book_editor',
                'book_isbn',
                'book_pdf'
            ]);
        });
    }
};
