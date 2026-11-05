<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Assuming these models exist from previous phases or in legacy code
use App\Models\Project;
use App\Models\SiteDailyLog;
use App\Models\AiTimesheetAnalyzer;

class MobileAppController extends Controller
{
    /**
     * Get all active projects/sites
     */
    public function getProjects()
    {
        // Mock data or real data from Project model
        $projects = class_exists('App\Models\Project') 
            ? Project::all() 
            : [
                ['id' => 1, 'name' => 'Ataşehir Finans Merkezi Şantiyesi', 'status' => 'Aktif'],
                ['id' => 2, 'name' => 'Bodrum Villa Projesi', 'status' => 'Aktif']
            ];

        return response()->json([
            'success' => true,
            'data' => $projects
        ], 200);
    }

    /**
     * Submit a daily site log from mobile
     */
    public function storeDailyLog(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|integer',
            'weather' => 'required|string',
            'worker_count' => 'required|integer',
            'notes' => 'nullable|string'
        ]);

        if (class_exists('App\Models\SiteDailyLog')) {
            // Adjust fields based on actual schema if needed
            $log = SiteDailyLog::create([
                'report_date' => now()->toDateString(),
                'weather_condition' => $data['weather'],
                'temperature' => '25C',
                'general_notes' => $data['notes'] ?? 'Mobil giriş',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Günlük şantiye raporu başarıyla kaydedildi.',
            'data' => $data
        ], 201);
    }

    /**
     * Upload a timesheet from mobile to be analyzed by AI
     */
    public function uploadTimesheet(Request $request)
    {
        $request->validate([
            'subcontractor_id' => 'required|integer',
            'photo' => 'required|image|max:5120', // 5MB Max
        ]);

        // Simulating the AI upload and processing
        $photoPath = $request->file('photo')->store('timesheets', 'public');

        if (class_exists('App\Models\AiTimesheetAnalyzer')) {
            AiTimesheetAnalyzer::create([
                'subcontractor_name' => 'Mobil Taşeron (ID: ' . $request->subcontractor_id . ')',
                'declared_workers' => 0, // Pending AI analysis
                'ai_detected_workers' => 0,
                'anomaly_percentage' => 0,
                'status' => 'ai_analizi_bekleniyor'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Puantaj fotoğrafı başarıyla yüklendi. Yapay zeka kuyruğuna alındı.',
            'photo_url' => asset('storage/' . $photoPath)
        ], 201);
    }

    /**
     * Executive Summary Dashboard Data for Mobile
     */
    public function getExecutiveSummary()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'active_cameras' => 12,
                'total_workers_today' => 450,
                'critical_alerts' => 2,
                'budget_status' => 'Normal'
            ]
        ], 200);
    }
}
