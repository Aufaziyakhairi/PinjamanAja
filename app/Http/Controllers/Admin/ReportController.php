<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = Loan::query()->with(['borrower', 'items.tool', 'loanReturn']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $loans = $query->orderByDesc('id')->paginate(20)->withQueryString();

        return view('admin.reports.index', compact('loans'));
    }

    public function print(Request $request): View
    {
        $query = Loan::query()->with(['borrower', 'items.tool', 'loanReturn']);

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->date('to'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $loans = $query->orderByDesc('id')->get();

        return view('admin.reports.print', compact('loans'));
    }
}
