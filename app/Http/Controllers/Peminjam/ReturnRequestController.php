<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Enums\ReturnStatus;
use App\Models\Loan;
use App\Models\LoanReturn;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnRequestController extends Controller
{
    public function store(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($loan->status !== LoanStatus::Approved) {
            return back()->withErrors(['loan' => 'Hanya peminjaman yang disetujui yang bisa diajukan pengembalian.']);
        }

        if ($loan->loanReturn()->exists()) {
            return back()->withErrors(['loan' => 'Pengembalian sudah diajukan.']);
        }

        DB::transaction(function () use ($loan, $request) {
            $return = LoanReturn::create([
                'loan_id' => $loan->id,
                'requested_by' => $request->user()->id,
                'status' => ReturnStatus::Requested,
                'requested_at' => now(),
            ]);

            ActivityLogger::log('return.requested', $return, ['loan_id' => $loan->id], $request);
        });

        return back()->with('status', 'Permintaan pengembalian dikirim.');
    }
}
