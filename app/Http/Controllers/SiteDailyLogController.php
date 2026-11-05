<?php

namespace App\Http\Controllers;

use App\Models\SiteDailyLog;
use App\Models\Project;
use Illuminate\Http\Request;

class SiteDailyLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = SiteDailyLog::orderBy('log_date', 'desc')->paginate(15);
        $projects = Project::pluck('name', 'id');

        $stats = [
            'total_logs' => SiteDailyLog::count(),
            'total_personnel' => SiteDailyLog::sum('total_workers') + SiteDailyLog::sum('subcontractor_workers'),
            'signed_count' => SiteDailyLog::where('is_signed', true)->count(),
            'pending_sign_count' => SiteDailyLog::where('is_signed', false)->count(),
        ];

        return view('site_daily_logs.index', compact('items', 'projects', 'stats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'log_date' => 'required|date',
            'weather_condition' => 'required|string',
            'temperature' => 'required|integer',
            'total_workers' => 'required|integer|min:0',
            'subcontractor_workers' => 'required|integer|min:0',
            'manufacturing_details' => 'required|string',
            'site_manager_name' => 'required|string',
            'is_signed' => 'nullable|boolean',
        ]);

        SiteDailyLog::create(array_merge($validated, [
            'is_signed' => $request->has('is_signed'),
        ]));

        return back()->with('success', 'Yasal Şantiye Günlük Defteri kaydı oluşturuldu.');
    }

    public function update(Request $request, SiteDailyLog $siteDailyLog)
    {
        $siteDailyLog->update([
            'is_signed' => $request->has('is_signed') ? true : $siteDailyLog->is_signed,
        ]);

        return back()->with('success', 'Günlük defteri imzası güncellendi.');
    }

    public function destroy(SiteDailyLog $siteDailyLog)
    {
        $siteDailyLog->delete();
        return back()->with('success', 'Şantiye günlük kaydı silindi.');
    }
}
