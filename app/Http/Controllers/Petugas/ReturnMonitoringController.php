<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Enums\ReturnStatus;
use App\Models\LoanReturn;
use App\Support\ActivityLogger;
use App\Support\FineCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReturnMonitoringController extends Controller
{
    public function index(): View
    {
        $returns = LoanReturn::query()
            ->with(['loan.borrower', 'loan.items.tool', 'requester'])
            ->where('status', ReturnStatus::Requested->value)
            ->orderBy('requested_at')
            ->paginate(10);

        return view('petugas.returns.index', compact('returns'));
    }

    public function receive(Request $request, LoanReturn $return): RedirectResponse
    {
        DB::transaction(function () use ($return, $request) {
            $return->load('loan.items');

            if ($return->status !== ReturnStatus::Requested) {
                abort(422, 'Pengembalian bukan status requested.');
            }

            $receivedAt = now();
            $fine = FineCalculator::forLoan($return->loan, $receivedAt);

            $return->status = ReturnStatus::Received;
            $return->received_by = $request->user()->id;
            $return->received_at = $receivedAt;
            $return->fine_days = $fine['days'];
            $return->fine_amount = $fine['amount'];
            $return->save();

            $return->loan->status = LoanStatus::Returned;
            $return->loan->returned_at = $receivedAt;
            $return->loan->save();

            ActivityLogger::log('return.received', $return, [], $request);
        });

        return back()->with('status', 'Pengembalian diterima.');
    }

    public function reject(Request $request, LoanReturn $return): RedirectResponse
    {
        if ($return->status !== ReturnStatus::Requested) {
            abort(422, 'Pengembalian bukan status requested.');
        }

        $return->status = ReturnStatus::Rejected;
        $return->received_by = $request->user()->id;
        $return->received_at = now();
        $return->save();

        ActivityLogger::log('return.rejected', $return, [], $request);

        return back()->with('status', 'Pengembalian ditolak.');
    }
}
