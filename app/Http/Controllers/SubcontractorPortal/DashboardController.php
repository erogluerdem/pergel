<?php

namespace App\Http\Controllers\SubcontractorPortal;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('subcontractor')->user();

        $contracts = $user->contracts()->with('project')->orderBy('contract_name')->get();
        if ($contracts->isEmpty()) {
            $contracts = $user->subcontractor
                ->contracts()
                ->with('project')
                ->orderBy('contract_name')
                ->get();
        }

        $serviceEntriesQuery = $user->serviceEntries()->with('project')->latest('entry_date');
        $materialEntriesQuery = $user->materialEntries()->with('project')->latest('delivery_date');

        $serviceStats = [
            'total' => (clone $serviceEntriesQuery)->count(),
            'submitted' => (clone $serviceEntriesQuery)->where('status', 'submitted')->count(),
            'approved' => (clone $serviceEntriesQuery)->where('status', 'approved')->count(),
            'draft' => (clone $serviceEntriesQuery)->where('status', 'draft')->count(),
        ];

        $materialStats = [
            'total' => (clone $materialEntriesQuery)->count(),
            'submitted' => (clone $materialEntriesQuery)->where('status', 'submitted')->count(),
            'approved' => (clone $materialEntriesQuery)->where('status', 'approved')->count(),
            'draft' => (clone $materialEntriesQuery)->where('status', 'draft')->count(),
        ];

        $recentServiceEntries = $serviceEntriesQuery->limit(5)->get();
        $recentMaterialEntries = $materialEntriesQuery->limit(5)->get();

        return view('subcontractor-portal.dashboard.index', compact(
            'user',
            'contracts',
            'serviceStats',
            'materialStats',
            'recentServiceEntries',
            'recentMaterialEntries'
        ));
    }
}
