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
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('abstract');
            $table->text('keywords');
            $table->foreignId('publication_type_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->boolean('is_published')->default(false);
            $table->enum('admin_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('dosen_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_feedback')->nullable();
            $table->text('dosen_feedback')->nullable();
            $table->timestamp('submission_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
