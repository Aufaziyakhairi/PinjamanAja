<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when($request->string('action')->toString(), fn ($q) => $q->where('action', $request->string('action')->toString()))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}
