<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use PDF;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::query();

        // Filter tipe aktivitas
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter role
        if ($request->filled('user_role')) {
            $query->where('user_role', $request->user_role);
        }

        // Filter tanggal
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ✅ Fix: search harus pakai where-group agar tidak bentrok dengan filter lain
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('activity_description', 'like', "%{$search}%");
            });
        }

        $logs          = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $activityTypes = ActivityLog::select('activity_type')->distinct()->orderBy('activity_type')->get();
        $userRoles     = ActivityLog::select('user_role')->whereNotNull('user_role')->distinct()->orderBy('user_role')->get();

        return view('admin.activity_log', compact('logs', 'activityTypes', 'userRoles'));
    }

    public function exportPdf(Request $request)
    {
        $query = ActivityLog::query();

        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }
        if ($request->filled('user_role')) {
            $query->where('user_role', $request->user_role);
        }
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('activity_description', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderByDesc('created_at')->get();
        $pdf  = PDF::loadView('admin.activity_log_pdf', compact('logs'));

        return $pdf->download('activity_log_' . now()->format('Ymd_His') . '.pdf');
    }
}