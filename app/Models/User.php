<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identity_number',
        'phone',
        'gender',
        'class_id',
        'avatar',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    public function isBendahara(): bool
    {
        return $this->role === 'bendahara';
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Courses taught if teacher
    public function teachingCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Bills if student
    public function bills(): HasMany
    {
        return $this->hasMany(StudentBill::class, 'student_id');
    }

    // Submissions if student
    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class, 'student_id');
    }

    // Quiz attempts if student
    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class, 'student_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function examCards(): HasMany
    {
        return $this->hasMany(ExamCard::class, 'user_id');
    }

    // Elective subjects chosen by student
    public function electiveSubjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_elective_subjects', 'student_id', 'subject_id')
            ->withPivot('academic_year', 'status')
            ->withTimestamps();
    }

    /**
     * Get the grade level from class name (e.g. "X MIPA 1" => "10", "XI IPS 1" => "11", "XII Keagamaan" => "12")
     */
    public function getGradeLevelAttribute(): ?string
    {
        if (!$this->schoolClass) return null;
        $name = $this->schoolClass->name;
        if (preg_match('/^XII/i', $name)) return '12';
        if (preg_match('/^XI/i', $name)) return '11';
        if (preg_match('/^X/i', $name)) return '10';
        return null;
    }
}
