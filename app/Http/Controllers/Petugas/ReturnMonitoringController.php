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
    public function index(Request $request): View
    {
        $status = $request->string('status')->toString();
        $allowed = array_merge(['', 'all'], array_map(fn (ReturnStatus $s) => $s->value, ReturnStatus::cases()));
        if (!in_array($status, $allowed, true)) {
            abort(422, 'Status filter tidak valid.');
        }

        $returns = LoanReturn::query()
            ->with(['loan.borrower', 'loan.items.tool', 'requester', 'receiver'])
            ->when($status && $status !== 'all', fn ($q) => $q->where('status', $status), fn ($q) => $q->where('status', ReturnStatus::Requested->value))
            ->orderByDesc('requested_at')
            ->paginate(10)
            ->withQueryString();

        $statuses = ReturnStatus::cases();

        return view('petugas.returns.index', compact('returns', 'statuses', 'status'));
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

            ActivityLogger::log('return.received', $return, [
                'loan_id' => $return->loan_id,
                'due_at' => $return->loan?->due_at?->toDateString(),
                'received_at' => $receivedAt->toDateTimeString(),
                'fine_days' => $fine['days'],
                'fine_amount' => $fine['amount'],
            ], $request);
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
