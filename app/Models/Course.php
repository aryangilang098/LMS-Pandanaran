<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
        'day',
        'time_start',
        'time_end',
        'room',
        'description',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'course_id')->latest();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'course_id')->latest();
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class, 'course_id')->latest();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'course_id')->latest();
    }
}
