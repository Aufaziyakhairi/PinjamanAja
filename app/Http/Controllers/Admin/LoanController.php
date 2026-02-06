<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Tool;
use App\Models\User;
use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $loans = Loan::query()
            ->with(['borrower', 'approver', 'items.tool'])
            ->when($request->string('status')->toString(), fn ($q) => $q->where('status', $request->string('status')->toString()))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $statuses = LoanStatus::cases();
        return view('admin.loans.index', compact('loans', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::query()->orderBy('name')->get();
        $tools = Tool::query()
            ->with('category')
            ->whereDoesntHave('loanItems.loan', function ($q) {
                $q->whereIn('status', [LoanStatus::Pending->value, LoanStatus::Approved->value]);
            })
            ->orderBy('name')
            ->get();
        return view('admin.loans.create', compact('users', 'tools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'tool_id' => ['required', 'exists:tools,id'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $loan = DB::transaction(function () use ($validated, $request) {
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
                'user_id' => $validated['user_id'],
                'status' => LoanStatus::Pending,
                'due_at' => $validated['due_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $loan->items()->create([
                'tool_id' => $tool->id,
                'qty' => 1,
            ]);

            ActivityLogger::log('loan.created', $loan, ['by' => 'admin'], $request);

            return $loan;
        });

        return redirect()->route('admin.loans.edit', $loan)->with('status', 'Peminjaman dibuat (status: pending).');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan): View
    {
        $loan->load(['borrower', 'approver', 'items.tool.category', 'loanReturn']);
        return view('admin.loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan): View
    {
        $loan->load(['borrower', 'approver', 'items.tool']);
        $statuses = LoanStatus::cases();
        return view('admin.loans.edit', compact('loan', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected,returned'],
            'due_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($loan, $validated, $request) {
            $loan->load('items');
            $oldStatus = $loan->status;
            $newStatus = LoanStatus::from($validated['status']);

            // If switching into approved, ensure tools are not already borrowed.
            if ($oldStatus !== LoanStatus::Approved && $newStatus === LoanStatus::Approved) {
                $loan->approved_by = $request->user()->id;
                $loan->approved_at = now();
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
            }

            if ($newStatus === LoanStatus::Rejected) {
                $loan->rejected_at = $loan->rejected_at ?? now();
            }

            if ($newStatus === LoanStatus::Returned) {
                $loan->returned_at = $loan->returned_at ?? now();
            }

            $loan->status = $newStatus;
            $loan->due_at = $validated['due_at'] ?? null;
            $loan->notes = $validated['notes'] ?? null;
            $loan->save();

            ActivityLogger::log('loan.updated', $loan, ['old' => $oldStatus->value, 'new' => $newStatus->value], $request);
        });

        return redirect()->route('admin.loans.edit', $loan)->with('status', 'Peminjaman diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Loan $loan): RedirectResponse
    {
        DB::transaction(function () use ($loan, $request) {
            $loan->load('items');

            ActivityLogger::log('loan.deleted', $loan, ['status' => $loan->status->value], $request);
            $loan->delete();
        });

        return redirect()->route('admin.loans.index')->with('status', 'Peminjaman dihapus.');
    }
}
