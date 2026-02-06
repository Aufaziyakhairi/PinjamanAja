<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Enums\ReturnStatus;
use App\Models\Loan;
use App\Models\LoanReturn;
use App\Support\ActivityLogger;
use App\Support\FineCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoanReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $returns = LoanReturn::query()
            ->with(['loan.borrower', 'requester', 'receiver'])
            ->when($request->string('status')->toString(), fn ($q) => $q->where('status', $request->string('status')->toString()))
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $statuses = ReturnStatus::cases();
        return view('admin.returns.index', compact('returns', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $loans = Loan::query()->with('borrower')->orderByDesc('id')->limit(100)->get();
        return view('admin.returns.create', compact('loans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'loan_id' => ['required', 'exists:loans,id', 'unique:loan_returns,loan_id'],
            'status' => ['required', 'in:requested,received,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        $return = DB::transaction(function () use ($validated, $request) {
            $loan = Loan::query()->with('items')->lockForUpdate()->findOrFail($validated['loan_id']);

            $receivedAt = $validated['status'] === 'received' ? now() : null;
            $fine = $receivedAt ? FineCalculator::forLoan($loan, $receivedAt) : ['days' => 0, 'amount' => 0];

            $return = LoanReturn::create([
                'loan_id' => $loan->id,
                'requested_by' => $request->user()->id,
                'received_by' => in_array($validated['status'], ['received', 'rejected'], true) ? $request->user()->id : null,
                'status' => $validated['status'],
                'requested_at' => now(),
                'received_at' => $receivedAt,
                'fine_days' => $receivedAt ? $fine['days'] : 0,
                'fine_amount' => $receivedAt ? $fine['amount'] : 0,
                'notes' => $validated['notes'] ?? null,
            ]);

            if ($return->status === ReturnStatus::Received) {
                $loan->status = LoanStatus::Returned;
                $loan->returned_at = $receivedAt ?? now();
                $loan->save();
            }

            ActivityLogger::log('return.created', $return, ['loan_id' => $loan->id], $request);
            return $return;
        });

        return redirect()->route('admin.returns.edit', $return)->with('status', 'Pengembalian dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return redirect()->route('admin.returns.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoanReturn $return): View
    {
        $return->load(['loan.items.tool', 'loan.borrower', 'requester', 'receiver']);
        $statuses = ReturnStatus::cases();
        return view('admin.returns.edit', compact('return', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoanReturn $return): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:requested,received,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($return, $validated, $request) {
            $return->load('loan.items');
            $old = $return->status;
            $new = ReturnStatus::from($validated['status']);

            // Transition to received -> restore stock and mark loan returned.
            if ($old !== ReturnStatus::Received && $new === ReturnStatus::Received) {
                $receivedAt = now();
                $fine = FineCalculator::forLoan($return->loan, $receivedAt);

                $return->received_by = $request->user()->id;
                $return->received_at = $receivedAt;
                $return->fine_days = $fine['days'];
                $return->fine_amount = $fine['amount'];

                $return->loan->status = LoanStatus::Returned;
                $return->loan->returned_at = $receivedAt;
                $return->loan->save();
            }

            // Transition away from received -> clear fine metadata (fine applies only when received).
            if ($old === ReturnStatus::Received && $new !== ReturnStatus::Received) {
                $return->fine_days = 0;
                $return->fine_amount = 0;
            }

            $return->status = $new;
            $return->notes = $validated['notes'] ?? null;
            $return->save();

            ActivityLogger::log('return.updated', $return, ['old' => $old->value, 'new' => $new->value], $request);
        });

        return redirect()->route('admin.returns.edit', $return)->with('status', 'Pengembalian diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, LoanReturn $return): RedirectResponse
    {
        ActivityLogger::log('return.deleted', $return, ['status' => $return->status->value], $request);
        $return->delete();

        return redirect()->route('admin.returns.index')->with('status', 'Pengembalian dihapus.');
    }
}
