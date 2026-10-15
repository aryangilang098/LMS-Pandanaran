<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Classes / Rombel
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "X MIPA 1", "XI IPS 2"
            $table->string('academic_year')->default('2025/2026');
            $table->string('homeroom_teacher_name')->nullable();
            $table->timestamps();
        });

        // 2. Subjects / Mata Pelajaran
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g. "QUR-10", "FIQ-10"
            $table->string('name'); // e.g. "Al-Qur'an Hadits", "Fikih"
            $table->string('category')->default('umum'); // keagamaan, umum, peminatan
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        // 3. Courses / Jadwal Pelajaran Kelas
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->string('day')->nullable(); // e.g. "Senin"
            $table->string('time_start')->nullable(); // "07:30"
            $table->string('time_end')->nullable(); // "09:00"
            $table->string('room')->nullable(); // "R. 204"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Learning Materials
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable(); // pdf, docx, video, link
            $table->string('external_link')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // 5. Assignments
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_attachment')->nullable();
            $table->dateTime('due_date');
            $table->integer('max_score')->default(100);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // 6. Assignment Submissions
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->text('student_note')->nullable();
            $table->integer('score')->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->string('status')->default('submitted'); // submitted, graded, late
            $table->dateTime('submitted_at');
            $table->timestamps();
        });

        // 7. Online Quizzes / Exams
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->dateTime('due_date')->nullable();
            $table->integer('passing_score')->default(75);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        // 8. Quiz Questions
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->text('question');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->string('correct_option', 10); // A, B, C, D
            $table->integer('score_weight')->default(20);
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        // 9. Quiz Attempts
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->json('answers_json')->nullable();
            $table->decimal('score', 5, 2)->default(0);
            $table->integer('total_correct')->default(0);
            $table->integer('total_questions')->default(0);
            $table->string('status')->default('in_progress'); // in_progress, completed
            $table->dateTime('started_at');
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        // 10. Attendances
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('title');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 11. Attendance Records
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('hadir'); // hadir, izin, sakit, alpa
            $table->string('note')->nullable();
            $table->timestamps();
        });

        // 12. Fee Types
        Schema::create('fee_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('default_amount', 12, 2);
            $table->boolean('is_monthly')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 13. Student Bills
        Schema::create('student_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_code')->unique();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('fee_type_id')->constrained('fee_types')->cascadeOnDelete();
            $table->string('title');
            $table->integer('month')->nullable(); // 1-12
            $table->integer('year')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->string('status')->default('unpaid'); // unpaid, pending, paid
            $table->timestamps();
        });

        // 14. Payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_code')->unique();
            $table->foreignId('bill_id')->constrained('student_bills')->cascadeOnDelete();
            $table->decimal('amount_paid', 12, 2);
            $table->string('payment_method'); // qris, bca_va, bri_va, mandiri_va, manual_transfer, cash
            $table->string('va_number')->nullable();
            $table->string('proof_file')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, success, rejected
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 15. Posts / News & Announcements
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('berita'); // berita, agenda, prestasi, pengumuman
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('thumbnail')->nullable();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->date('event_date')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
        });

        // 16. Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('unread'); // unread, read
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('student_bills');
        Schema::dropIfExists('fee_types');
        Schema::dropIfExists('attendance_records');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('classes');
    }
};
