<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Project;

class ClaimsController extends Controller
{
    public function index(Request $request)
    {
        $query = Claim::with('project');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($s) use ($q){
                $s->where('title', 'like', "%{$q}%")
                  ->orWhere('number', 'like', "%{$q}%")
                  ->orWhere('claimant', 'like', "%{$q}%");
            });
        }

        $claims = $query->orderBy('created_at', 'desc')->paginate(25)->appends($request->query());

        // summary cards
        $totalOpen = Claim::where('status', 'İncelemede')->count();
        $totalPotentialCost = Claim::where('status', 'İncelemede')->sum('amount_requested');
        $totalPotentialDays = Claim::where('status', 'İncelemede')->sum('days_requested');
        $awaitingResponse = Claim::where('status', 'Cevap Bekleniyor')->count();

        $projects = Project::orderBy('name')->get();

        return view('contracts.claims.index', compact('claims','totalOpen','totalPotentialCost','totalPotentialDays','awaitingResponse','projects'));
    }

    public function show(Claim $claim)
    {
        // eager load relations and placeholder related links
        $claim->load('project', 'responsible');
        return view('contracts.claims.show', compact('claim'));
    }

    // placeholder create/store (expand later)
    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('contracts.claims.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'claimant' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:50',
            'amount_requested' => 'nullable|numeric|min:0',
            'days_requested' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Build a structured claim number: HT-{project_code}-{YYYY}-{seq}
        $projectCode = null;
        if (!empty($data['project_id'])) {
            $project = Project::find($data['project_id']);
            if ($project) {
                $projectCode = $project->project_code ?: 'P' . $project->id;
            }
        }
        if (!$projectCode) {
            $projectCode = 'GEN';
        }

        $year = date('Y');
        $prefix = sprintf('HT-%s-%s', strtoupper(preg_replace('/[^A-Z0-9]/','', $projectCode)), $year);

        // find last sequence with same prefix
        $last = Claim::where('number','like', $prefix . '-%')->orderBy('id','desc')->first();
        $seq = 1;
        if ($last && preg_match('/-(\d+)$/', $last->number, $m)) {
            $seq = intval($m[1]) + 1;
        } else {
            // fallback: count existing
            $count = Claim::where('number','like', $prefix . '-%')->count();
            $seq = $count + 1;
        }

        $number = sprintf('%s-%02d', $prefix, $seq);

        $claim = Claim::create(array_merge($data, ['number' => $number]));

        return redirect()->route('contracts.claims.show', $claim->id)->with('success','Hak talebi oluşturuldu. No: ' . $number);
    }
}
