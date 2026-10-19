<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'name',
        'academic_year',
        'homeroom_teacher_name',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'class_id')->where('role', 'siswa');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'class_id');
    }

    public function getGradeLevel(): string
    {
        $name = strtoupper(trim($this->name));
        if (preg_match('/\bXII\b/', $name) || str_starts_with($name, 'XII')) {
            return '12';
        }
        if (preg_match('/\bXI\b/', $name) || str_starts_with($name, 'XI')) {
            return '11';
        }
        if (preg_match('/\bX\b/', $name) || str_starts_with($name, 'X')) {
            return '10';
        }
        return '10';
    }

    public function isGrade10(): bool
    {
        return $this->getGradeLevel() === '10';
    }

    public function isSeniorGrade(): bool
    {
        return in_array($this->getGradeLevel(), ['11', '12']);
    }
}
