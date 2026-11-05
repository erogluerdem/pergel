<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,manager', ['except' => ['index', 'show']]);
    }

    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->input('model_type'));
        }

        if ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->input('end_date'));
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(25);
        $users = \App\Models\User::pluck('name', 'id');

        return view('activity_logs.index', compact('activities', 'users'));
    }

    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        
        return view('activity_logs.show', compact('activityLog'));
    }

    public function userAuditTrail(Request $request)
    {
        $userId = $request->input('user_id');
        $user = $userId ? \App\Models\User::find($userId) : auth()->user();
        if (!$user) {
            $user = \App\Models\User::first();
        }

        $activities = ActivityLog::where('user_id', $user ? $user->id : 0)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('activity_logs.user_audit_trail', compact('user', 'activities'));
    }

    public function modelAuditTrail(Request $request)
    {
        $modelType = $request->input('model_type');
        $modelId = $request->input('model_id');

        $activities = ActivityLog::where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('activity_logs.model_audit_trail', compact('activities', 'modelType', 'modelId'));
    }

    public function complianceReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        $activities = ActivityLog::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = [
            'total_actions' => $activities->count(),
            'unique_users' => $activities->pluck('user_id')->unique()->count(),
            'actions_by_type' => $activities->groupBy('action')->map->count(),
            'models_affected' => $activities->groupBy('model_type')->map->count(),
        ];

        return view('activity_logs.compliance_report', compact('activities', 'summary', 'startDate', 'endDate'));
    }
}
