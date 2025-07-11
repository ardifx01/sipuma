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
            $table->string('journal_name')->nullable()->after('keywords');
            $table->string('journal_url')->nullable()->after('journal_name');
            $table->string('indexing')->nullable()->after('journal_url');
            $table->string('doi')->nullable()->after('indexing');
            $table->string('issn')->nullable()->after('doi');
            $table->string('publisher')->nullable()->after('issn');
            $table->date('publication_date')->nullable()->after('publisher');
            $table->string('volume')->nullable()->after('publication_date');
            $table->string('issue')->nullable()->after('volume');
            $table->string('pages')->nullable()->after('issue');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'journal_name',
                'journal_url',
                'indexing',
                'doi',
                'issn',
                'publisher',
                'publication_date',
                'volume',
                'issue',
                'pages'
            ]);
        });
    }
};
