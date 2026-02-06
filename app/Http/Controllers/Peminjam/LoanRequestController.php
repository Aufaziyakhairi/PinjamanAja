<?php

namespace App\Http\Controllers\Peminjam;

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

class LoanRequestController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::query()
            ->with(['items.tool', 'loanReturn'])
            ->where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('peminjam.loans.index', compact('loans'));
    }

    public function create(): View
    {
        $tools = Tool::query()
            ->with('category')
            ->whereDoesntHave('loanItems.loan', function ($q) {
                $q->whereIn('status', [LoanStatus::Pending->value, LoanStatus::Approved->value]);
            })
            ->orderBy('name')
            ->get();
        return view('peminjam.loans.create', compact('tools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tool_id' => ['required', 'exists:tools,id'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $tool = Tool::query()->lockForUpdate()->findOrFail($validated['tool_id']);

            $busy = LoanItem::query()
                ->where('tool_id', $tool->id)
                ->whereHas('loan', function ($q) {
                    $q->whereIn('status', [LoanStatus::Pending->value, LoanStatus::Approved->value]);
                })
                ->exists();

            if ($busy) {
                abort(422, 'Alat sedang dipinjam atau sedang diajukan oleh peminjam lain.');
            }

            $loan = Loan::create([
                'user_id' => $request->user()->id,
                'status' => LoanStatus::Pending,
                'due_at' => $validated['due_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $loan->items()->create([
                'tool_id' => $tool->id,
                'qty' => 1,
            ]);

            ActivityLogger::log('loan.requested', $loan, ['tool_id' => $tool->id, 'qty' => 1], $request);
        });

        return redirect()->route('peminjam.loans.index')->with('status', 'Pengajuan peminjaman dikirim.');
    }
}
