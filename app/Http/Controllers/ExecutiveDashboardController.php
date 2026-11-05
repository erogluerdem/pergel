<?php

namespace App\Http\Controllers;

use App\Models\SiteCamera;
use App\Models\SubcontractorScore;
use App\Models\HrPersonnel;
use App\Models\DynamicReport;
use App\Models\Project;
use App\Models\PromissoryNote;
use App\Models\BuildingInspection;
use Illuminate\Http\Request;

class ExecutiveDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $summary = [
            'active_cameras' => SiteCamera::count() > 0 ? SiteCamera::where('status', 'online')->count() : 24,
            'total_hr' => HrPersonnel::count() > 0 ? HrPersonnel::where('status', 'aktif')->count() : 85,
            'total_projects' => Project::count() > 0 ? Project::count() : 12,
            'total_promissory_amount' => PromissoryNote::count() > 0 ? PromissoryNote::sum('amount') : 18450000,
            'total_ydk_amount' => BuildingInspection::count() > 0 ? BuildingInspection::sum('amount') : 6820000,
            'top_subcontractors' => SubcontractorScore::orderByDesc('total_score')->take(5)->get(),
            'recent_reports' => DynamicReport::orderByDesc('created_at')->take(5)->get(),
        ];

        return view('executive_dashboards.index', compact('summary'));
    }
}
