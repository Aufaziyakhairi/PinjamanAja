<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Tool;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanApprovalController extends Controller
{
    public function index(): View
    {
        $loans = Loan::query()
            ->with(['borrower', 'items.tool'])
            ->where('status', LoanStatus::Pending->value)
            ->orderBy('created_at')
            ->paginate(10);

        return view('petugas.approvals.index', compact('loans'));
    }

    public function approve(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'due_at' => ['required', 'date', 'after_or_equal:now'],
        ]);

        DB::transaction(function () use ($loan, $request, $validated) {
            $loan->load('items');
            if ($loan->status !== LoanStatus::Pending) {
                abort(422, 'Peminjaman bukan status pending.');
            }

            foreach ($loan->items as $item) {
                $tool = Tool::query()->lockForUpdate()->findOrFail($item->tool_id);

                $busy = LoanItem::query()
                    ->where('tool_id', $tool->id)
                    ->whereHas('loan', function ($q) use ($loan) {
                        $q->where('status', LoanStatus::Approved->value)
                            ->where('id', '!=', $loan->id);
                    })
                    ->exists();

                if ($busy) {
                    abort(422, "Alat {$tool->name} sedang dipinjam.");
                }
            }

            $loan->status = LoanStatus::Approved;
            $loan->approved_by = $request->user()->id;
            $loan->approved_at = now();
            $loan->due_at = $validated['due_at'];
            $loan->save();

            ActivityLogger::log('loan.approved', $loan, ['due_at' => $validated['due_at']], $request);
        });

        return back()->with('status', 'Peminjaman disetujui.');
    }

    public function reject(Request $request, Loan $loan): RedirectResponse
    {
        if ($loan->status !== LoanStatus::Pending) {
            abort(422, 'Peminjaman bukan status pending.');
        }

        $loan->status = LoanStatus::Rejected;
        $loan->approved_by = $request->user()->id;
        $loan->rejected_at = now();
        $loan->save();

        ActivityLogger::log('loan.rejected', $loan, [], $request);

        return back()->with('status', 'Peminjaman ditolak.');
    }
}
