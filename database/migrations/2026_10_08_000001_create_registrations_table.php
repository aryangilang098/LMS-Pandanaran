<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nisn', 20);
            $table->string('phone', 20);
            $table->string('email');
            $table->string('school');             // Asal Sekolah (SMP/MTs)
            $table->string('program');            // tahfidz, sains, bilingual, reguler
            $table->string('password');           // stored hashed, for future account
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
