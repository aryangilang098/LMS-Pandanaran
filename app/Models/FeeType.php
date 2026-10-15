<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'default_amount',
        'is_monthly',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'default_amount' => 'decimal:2',
            'is_monthly' => 'boolean',
        ];
    }

    public function bills(): HasMany
    {
        return $this->hasMany(StudentBill::class, 'fee_type_id');
    }
}
