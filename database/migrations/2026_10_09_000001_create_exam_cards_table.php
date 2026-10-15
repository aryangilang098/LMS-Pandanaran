<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('exam_type', ['uts', 'uas'])->default('uts');
            $table->string('academic_year')->default('2026/2027');
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->string('exam_number')->nullable(); // e.g. UTS-2026-001
            $table->string('room_number')->nullable(); // e.g. Ruang 04 - R102
            $table->string('file_path')->nullable(); // optional PDF file upload
            $table->enum('status', ['approved', 'pending', 'suspended'])->default('approved');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exam_type', 'academic_year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_cards');
    }
};
