<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_code',
        'student_id',
        'fee_type_id',
        'title',
        'month',
        'year',
        'amount',
        'due_date',
        'status', // unpaid, pending, paid
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class, 'fee_type_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'bill_id')->latest();
    }

    public function successfulPayment()
    {
        return $this->payments()->where('status', 'success')->first();
    }

    public function latestPayment()
    {
        return $this->payments()->first();
    }
}
