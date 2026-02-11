<?php

namespace App\Support;

use App\Models\Loan;
use Carbon\CarbonInterface;

class FineCalculator
{
    /**
     * @return array{days:int, amount:int}
     */
    public static function forLoan(Loan $loan, CarbonInterface $receivedAt): array
    {
        if (!$loan->due_at) {
            return ['days' => 0, 'amount' => 0];
        }

        $dueAt = $loan->due_at;
        if ($receivedAt->lessThanOrEqualTo($dueAt)) {
            return ['days' => 0, 'amount' => 0];
        }

        $minutesLate = $dueAt->diffInMinutes($receivedAt);
        $days = (int) max(1, (int) ceil($minutesLate / 1440));
        $perDay = (int) config('loan.fine_per_day', 0);

        return [
            'days' => $days,
            'amount' => max(0, $days * $perDay),
        ];
    }
}
