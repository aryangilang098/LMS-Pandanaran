<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category',
        'icon',
        'grade_level',
        'is_elective',
    ];

    protected function casts(): array
    {
        return [
            'is_elective' => 'boolean',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'subject_id');
    }

    public function electiveStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_elective_subjects', 'subject_id', 'student_id')
            ->withPivot('academic_year', 'status')
            ->withTimestamps();
    }

    // Scope: mapel wajib (default)
    public function scopeRequired($query)
    {
        return $query->where('is_elective', false);
    }

    // Scope: mapel peminatan
    public function scopeElective($query)
    {
        return $query->where('is_elective', true);
    }

    // Scope: filter by grade level
    public function scopeForGrade($query, string $grade)
    {
        return $query->where(function ($q) use ($grade) {
            $q->where('grade_level', $grade)
              ->orWhere('grade_level', 'all')
              ->orWhere('grade_level', 'LIKE', "%{$grade}%");
        });
    }
}
