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

        $dueDay = $loan->due_at->startOfDay();
        $receivedDay = $receivedAt->copy()->startOfDay();

        if ($receivedDay->lessThanOrEqualTo($dueDay)) {
            return ['days' => 0, 'amount' => 0];
        }

        $days = $dueDay->diffInDays($receivedDay);
        $perDay = (int) config('loan.fine_per_day', 0);

        return [
            'days' => $days,
            'amount' => max(0, $days * $perDay),
        ];
    }
}
