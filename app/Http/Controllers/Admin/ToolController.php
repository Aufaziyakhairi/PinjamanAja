<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Models\Category;
use App\Models\Tool;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $tools = Tool::query()
            ->with('category')
            ->withCount([
                'loanItems as active_loan_items_count' => function ($q) {
                    $q->whereHas('loan', function ($qq) {
                        $qq->whereIn('status', [LoanStatus::Pending->value, LoanStatus::Approved->value]);
                    });
                },
            ])
            ->when($request->string('q')->toString(), function ($q) use ($request) {
                $term = $request->string('q')->toString();
                $q->where('name', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tools.index', compact('tools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.tools.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $tool = Tool::create($validated);
        ActivityLogger::log('tool.created', $tool);

        return redirect()->route('admin.tools.index')->with('status', 'Alat dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): RedirectResponse
    {
        return redirect()->route('admin.tools.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tool $tool): View
    {
        $categories = Category::query()->orderBy('name')->get();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tool $tool): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $tool->update($validated);
        ActivityLogger::log('tool.updated', $tool);

        return redirect()->route('admin.tools.index')->with('status', 'Alat diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tool $tool): RedirectResponse
    {
        if ($tool->loanItems()->exists()) {
            return back()->withErrors(['tool' => 'Alat masih punya riwayat peminjaman.']);
        }

        ActivityLogger::log('tool.deleted', $tool);
        $tool->delete();

        return redirect()->route('admin.tools.index')->with('status', 'Alat dihapus.');
    }
}
