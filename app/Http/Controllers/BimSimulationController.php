<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\BimModel;
use App\Models\Simulation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BimSimulationController extends Controller
{
    // List simulations
    public function index(Request $request)
    {
        $simulations = Simulation::orderBy('created_at','desc')->with('project')->get();
        return view('bim.simulations.index', compact('simulations'));
    }

    // Create form
    public function create(Request $request)
    {
        $projects = Project::orderBy('name')->get();
        $models = BimModel::orderBy('created_at','desc')->get();
        return view('bim.simulations.create', compact('projects','models'));
    }

    // Store simulation
    public function store(Request $request)
    {
        $data = $request->validate(['name'=>'required','type'=>'required','project_id'=>'required']);
        $sim = Simulation::create([
            'name'=>$data['name'],
            'type'=>$data['type'],
            'project_id'=>$data['project_id'],
            'status'=>'pending',
            'config'=>['models'=> $request->input('models',[])],
            'created_by'=>$request->user()->id ?? null
        ]);

        Log::info('Simulation created', ['id'=>$sim->id]);

        return redirect()->route('bim.simulations.show',['id'=>$sim->id]);
    }

    public function show(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        if ($sim->type === '5D') {
            return redirect()->route('bim.simulations.5d', ['id'=>$id]);
        }
        return redirect()->route('bim.simulations.4d', ['id'=>$id]);
    }

    public function play(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        if ($sim->type === '5D') {
            return redirect()->route('bim.simulations.5d', ['id'=>$id]);
        }
        return redirect()->route('bim.simulations.4d', ['id'=>$id]);
    }

    public function show4D(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        $model = null; $tasks = [];
        $modelId = data_get($sim->config,'models.0');
        if ($modelId) $model = BimModel::find($modelId);

        // try to get tasks from related project planning API if available
        try {
            $res = app(\App\Http\Controllers\ProjectPlanningController::class)->tasks($sim->project);
            $tasks = is_array($res) ? $res : ($res->original ?? []);
        } catch (\Exception $e) {
            // fallback stubs
            $tasks = [
                ['id'=>1,'name'=>'Kazıklar','start'=>'2025-10-01','end'=>'2025-10-10'],
                ['id'=>2,'name'=>'Kolon Montajı','start'=>'2025-10-11','end'=>'2025-11-01'],
                ['id'=>3,'name'=>'Duvar İnşası','start'=>'2025-11-02','end'=>'2025-12-15']
            ];
        }

        return view('bim.simulations.show-4d', ['sim'=>$sim,'model'=>$model,'tasks'=>$tasks,'id'=>$id]);
    }

    public function show5D(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        $model = null;
        $modelId = data_get($sim->config,'models.0');
        if ($modelId) $model = BimModel::find($modelId);

        // compute costs if not present
        if (empty($sim->result)) {
            $costs = $this->computeCosts($sim);
        } else {
            $costs = $sim->result;
        }

        $tasks = $sim->config['tasks'] ?? [
            ['id'=>1,'name'=>'Kazıklar','start'=>'2025-10-01','end'=>'2025-10-10'],
            ['id'=>2,'name'=>'Kolon Montajı','start'=>'2025-10-11','end'=>'2025-11-01']
        ];

        return view('bim.simulations.show-5d', compact('sim','model','tasks','costs','id'));
    }

    // Persist mappings between model element ids and task ids
    public function saveMapping(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        $data = $request->validate(['mappings'=>'required|array']);
        $config = $sim->config ?: [];
        // merge with existing mappings
        $existing = $config['mappings'] ?? [];
        $merged = array_merge($existing, $data['mappings']);
        $config['mappings'] = $merged;
        $sim->config = $config;
        $sim->save();
        return response()->json(['ok'=>true,'mappings'=>$merged]);
    }

    // Recompute costs on demand (AJAX)
    public function recomputeCosts(Request $request, $id)
    {
        $sim = Simulation::findOrFail($id);
        $series = $this->computeCosts($sim);
        return response()->json(['ok'=>true,'series'=>$series]);
    }

    // Compute simple S-curve (planned cumulative and actual cumulative) and save to simulation.result
    protected function computeCosts(Simulation $sim)
    {
        // Determine project budget
        $project = $sim->project;
        $budget = $project ? ($project->budget ?: 0) : 0;

        // Get tasks from project planning controller if possible
        try {
            $res = app(\App\Http\Controllers\ProjectPlanningController::class)->tasks($project);
            $tasks = is_array($res) ? $res : ($res->original ?? []);
        } catch (\Exception $e) {
            $tasks = [];
        }

        // fallback if simulation config has tasks
        if (empty($tasks) && isset($sim->config['tasks'])) {
            $tasks = $sim->config['tasks'];
        }

        // if still empty, create a small time window
        if (empty($tasks)) {
            $tasks = [
                ['id'=>1,'name'=>'İş A','start'=>'2025-10-01','end'=>'2025-10-31','cost'=>($budget?:100000)/3],
                ['id'=>2,'name'=>'İş B','start'=>'2025-11-01','end'=>'2025-11-30','cost'=>($budget?:100000)/3],
                ['id'=>3,'name'=>'İş C','start'=>'2025-12-01','end'=>'2026-01-15','cost'=>($budget?:100000)/3],
            ];
        }

        // compute task durations to distribute budget
        $startDates = array_map(fn($t)=>Carbon::parse($t['start']), $tasks);
        $endDates = array_map(fn($t)=>Carbon::parse($t['end']), $tasks);
        $globalStart = min($startDates);
        $globalEnd = max($endDates);

        $totalDuration = 0;
        $durations = [];
        foreach($tasks as $t){
            $d = Carbon::parse($t['start'])->diffInDays(Carbon::parse($t['end'])) + 1;
            $durations[] = $d; $totalDuration += $d;
        }

        // distribute planned costs proportional to duration
        $plannedPerTask = [];
        foreach($tasks as $i=>$t){
            $plannedPerTask[$t['id']] = round(($totalDuration>0 ? ($durations[$i]/$totalDuration) * $budget : ($budget/count($tasks))),2);
        }

        // Build monthly cumulative series from globalStart to globalEnd
        $cursor = $globalStart->copy()->startOfMonth();
        $endCursor = $globalEnd->copy()->endOfMonth();
        $series = [];
        $cumulativePlanned = 0; $cumulativeActual = 0;

        while($cursor->lte($endCursor)){
            // sum planned of tasks finishing in the current month
            $monthStart = $cursor->copy()->startOfMonth();
            $monthEnd = $cursor->copy()->endOfMonth();
            $monthlyPlanned = 0; $monthlyActual = 0;
            foreach($tasks as $t){
                $taskEnd = Carbon::parse($t['end'])->endOfDay();
                if ($taskEnd->between($monthStart, $monthEnd)){
                    $monthlyPlanned += $plannedPerTask[$t['id']] ?? 0;
                }
            }
            $cumulativePlanned += $monthlyPlanned;

            // actual: use project->spent proportionally across months if available
            $projectSpent = $project ? ($project->spent ?: 0) : 0;
            // naive actual: proportion of spent proportional to planned
            $monthlyActual = ($budget>0) ? ($monthlyPlanned * ($projectSpent / $budget)) : 0;
            $cumulativeActual += $monthlyActual;

            $series[] = [
                'date'=>$cursor->format('Y-m-d'),
                'monthly_planned'=>round($monthlyPlanned,2),
                'monthly_actual'=>round($monthlyActual,2),
                'planned'=>round($cumulativePlanned,2),
                'actual'=>round($cumulativeActual,2)
            ];
            $cursor->addMonth();
        }

        $sim->result = $series;
        $sim->save();
        return $series;
    }
}
