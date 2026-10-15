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
}
