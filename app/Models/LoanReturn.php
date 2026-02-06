<?php

namespace App\Models;

use App\Enums\ReturnStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'requested_by',
        'received_by',
        'status',
        'requested_at',
        'received_at',
        'fine_days',
        'fine_amount',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ReturnStatus::class,
            'requested_at' => 'datetime',
            'received_at' => 'datetime',
            'fine_days' => 'integer',
            'fine_amount' => 'integer',
        ];
    }

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
