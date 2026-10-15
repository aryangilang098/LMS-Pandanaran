<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'name',
        'nisn',
        'phone',
        'email',
        'school',
        'program',
        'password',
        'status',
        'admin_notes',
    ];

    protected $hidden = [
        'password',
    ];

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getProgramLabelAttribute(): string
    {
        return match ($this->program) {
            'tahfidz'   => 'Program Tahfidz Al-Qur\'an',
            'sains'     => 'Sains, Robotika & Informatika',
            'bilingual' => 'Bilingual Arab & Inggris',
            'reguler'   => 'Kelas Reguler / Umum',
            default     => ucfirst($this->program),
        };
    }
}
