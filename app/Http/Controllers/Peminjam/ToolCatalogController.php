<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Enums\LoanStatus;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ToolCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->orderBy('name')->get();

        $tools = Tool::query()
            ->with('category')
            ->withCount([
                'loanItems as active_loan_items_count' => function ($q) {
                    $q->whereHas('loan', function ($qq) {
                        $qq->whereIn('status', [LoanStatus::Pending->value, LoanStatus::Approved->value]);
                    });
                },
            ])
            ->when($request->integer('category_id'), fn ($q) => $q->where('category_id', $request->integer('category_id')))
            ->when($request->string('q')->toString(), function ($q) use ($request) {
                $term = $request->string('q')->toString();
                $q->where('name', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('peminjam.tools.index', compact('tools', 'categories'));
    }
}
