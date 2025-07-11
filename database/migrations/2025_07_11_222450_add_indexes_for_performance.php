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
            // Index untuk kolom yang sering digunakan dalam query
            $table->index('student_id');
            $table->index('admin_status');
            $table->index('dosen_status');
            $table->index('publication_status');
            $table->index('publication_type_id');
            $table->index(['admin_status', 'dosen_status']); // Composite index untuk query yang sering digabung
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            // Index untuk supervisor_id yang sering digunakan dalam query dosen
            $table->index('supervisor_id');
            $table->index('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // Index untuk email yang sering digunakan dalam pencarian
            $table->index('email');
        });

        Schema::table('reviews', function (Blueprint $table) {
            // Index untuk reviewer_id dan publication_id
            $table->index('reviewer_id');
            $table->index('publication_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['admin_status']);
            $table->dropIndex(['dosen_status']);
            $table->dropIndex(['publication_status']);
            $table->dropIndex(['publication_type_id']);
            $table->dropIndex(['admin_status', 'dosen_status']);
        });

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropIndex(['supervisor_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['reviewer_id']);
            $table->dropIndex(['publication_id']);
        });
    }
};
