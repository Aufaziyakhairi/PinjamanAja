<?php

namespace App\Models;

use App\Enums\LoanStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'status',
        'approved_at',
        'rejected_at',
        'returned_at',
        'due_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => LoanStatus::class,
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'returned_at' => 'datetime',
            'due_at' => 'date',
        ];
    }

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    public function loanReturn(): HasOne
    {
        return $this->hasOne(LoanReturn::class);
    }
}
