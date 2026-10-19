<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add grade_level and is_elective to subjects
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('grade_level')->default('all')->after('category'); // 'all', '10', '11', '12', '11-12'
            $table->boolean('is_elective')->default(false)->after('grade_level'); // false = wajib, true = peminatan
        });

        // Pivot table for students choosing elective subjects
        Schema::create('student_elective_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('academic_year')->default('2025/2026');
            $table->string('status')->default('active'); // active, dropped
            $table->timestamps();

            $table->unique(['student_id', 'subject_id', 'academic_year'], 'student_subject_year_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_elective_subjects');

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['grade_level', 'is_elective']);
        });
    }
};
