<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\Project;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Show list of programs with summary metrics
     */
    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->q.'%')
                  ->orWhere('manager', 'like', '%'.$request->q.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categoryMap = [
            'konut' => 'konut',
            'ticari' => 'ticari',
            'otel' => 'otel',
            'lojistik' => 'lojistik'
        ];

        $categoryOptions = ['' => 'Tümü', 'konut' => 'Konut Geliştirme', 'ticari' => 'Ticari Yatırımlar', 'otel' => 'Otel/Yatırım', 'lojistik' => 'Lojistik/Endüstri'];

        if ($request->filled('category') && isset($categoryMap[$request->category])) {
            $keyword = $categoryMap[$request->category];
            $query->where('strategic_objective', 'like', '%'.$keyword.'%');
        }

        $programs = $query->with(['projects','milestones'])->paginate(10)->withQueryString();

        $programs->getCollection()->transform(function ($program) {
            $projects = $program->projects;
            $projectCount = $projects->count();

            $statusToPercent = [
                'planning' => 5,
                'bidding' => 10,
                'contracted' => 20,
                'construction' => 50,
                'completed' => 100,
            ];

            $totalWeight = 0;
            $weightedProgress = 0;
            $totalBudget = 0;
            $totalSpent = 0;

            foreach ($projects as $p) {
                $budget = $p->budget ?: 0;
                $percent = isset($p->progress_percent) && $p->progress_percent !== null ? (float)$p->progress_percent : ($statusToPercent[$p->status] ?? 0);
                $spent = isset($p->spent) && $p->spent !== null ? (float)$p->spent : 0;

                $weight = $budget > 0 ? $budget : 1;
                $totalWeight += $weight;
                $weightedProgress += $percent * $weight;

                $totalBudget += $budget;
                $totalSpent += $spent;
            }

            $avgProgress = $totalWeight ? round($weightedProgress / $totalWeight, 1) : 0;

            $health = 'green';
            if ($avgProgress < 40 || ($totalBudget > 0 && $totalSpent > $totalBudget * 1.1)) {
                $health = 'red';
            } elseif ($avgProgress < 60 || ($totalBudget > 0 && $totalSpent > $totalBudget)) {
                $health = 'yellow';
            }

            $milestones = [];
            try {
                if (method_exists($program, 'milestones')) {
                    $milestones = $program->milestones()->orderBy('date','asc')->take(3)->get()->map(function($m){
                        return [
                            'title' => $m->title ?? ($m->name ?? 'Kilometre Taşı'),
                            'date' => isset($m->date) ? \Carbon\Carbon::parse($m->date)->format('Y-m-d') : null,
                            'status' => $m->status ?? null
                        ];
                    })->toArray();
                }
            } catch (\Exception $e) {
                $milestones = [];
            }

            $program->meta = [
                'project_count' => $projectCount,
                'progress_percent' => $avgProgress,
                'total_budget' => $totalBudget,
                'total_spent' => $totalSpent,
                'health' => $health,
                'milestones' => $milestones,
            ];

            return $program;
        });

        $stats = [
            'total_count' => Program::count(),
            'active_count' => Program::where('status', 'active')->count() ?: Program::count(),
            'total_budget' => Program::sum('total_budget'),
            'total_spent' => Program::sum('spent_to_date'),
        ];

        return view('portfolio.programs.index', compact('programs','categoryOptions','stats'));
    }

    public function create()
    {
        return view('portfolio.programs.create', ['program' => new Program]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager' => 'nullable|string|max:255',
            'strategic_objective' => 'nullable|string|max:500',
            'status' => 'nullable|string|max:50',
            'total_budget' => 'nullable|numeric',
            'spent_to_date' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $program = Program::create($data);

        return redirect()->route('portfolio.management.show', $program->id)->with('success', 'Program oluşturuldu.');
    }

    public function edit($id)
    {
        $program = Program::findOrFail($id);
        return view('portfolio.programs.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'manager' => 'nullable|string|max:255',
            'strategic_objective' => 'nullable|string|max:500',
            'status' => 'nullable|string|max:50',
            'total_budget' => 'nullable|numeric',
            'spent_to_date' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $program->update($data);

        return redirect()->route('portfolio.management.show', $program->id)->with('success', 'Program güncellendi.');
    }

    public function destroy($id)
    {
        $program = Program::findOrFail($id);
        Project::where('program_id', $program->id)->update(['program_id' => null]);
        $program->delete();

        return redirect()->route('portfolio.management.index')->with('success', 'Program silindi.');
    }

    public function show($id)
    {
        $program = Program::with(['projects','milestones','risks','resources'])->findOrFail($id);

        $projects = $program->projects;
        $statusToPercent = [
            'planning' => 5,
            'bidding' => 10,
            'contracted' => 20,
            'construction' => 50,
            'completed' => 100,
        ];

        $totalBudget = 0;
        $totalSpent = 0;
        $weightedProgress = 0;
        $totalWeight = 0;
        $risks = [];

        foreach ($projects as $p) {
            $budget = $p->budget ?: 0;
            $percent = isset($p->progress_percent) && $p->progress_percent !== null ? (float)$p->progress_percent : ($statusToPercent[$p->status] ?? 0);
            $spent = isset($p->spent) && $p->spent !== null ? (float)$p->spent : 0;

            $weight = $budget > 0 ? $budget : 1;
            $totalWeight += $weight;
            $weightedProgress += $percent * $weight;

            $totalBudget += $budget;
            $totalSpent += $spent;

            if ($p->status === 'construction' && $percent < 30) {
                $risks[] = ['project' => $p->name, 'issue' => 'Düşük ilerleme'];
            }
        }

        $avgProgress = $totalWeight ? round($weightedProgress / $totalWeight, 1) : 0;

        $programMeta = [
            'project_count' => $projects->count(),
            'progress_percent' => $avgProgress,
            'total_budget' => $totalBudget,
            'total_spent' => $totalSpent,
            'health' => ($avgProgress >= 60 ? 'green' : ($avgProgress >= 40 ? 'yellow' : 'red')),
            'risks' => $risks,
        ];

        $financialSeries = [
            'labels' => [],
            'planned' => [],
            'actual' => [],
        ];

        $start = $program->start_date ? \Carbon\Carbon::parse($program->start_date) : null;
        $end = $program->end_date ? \Carbon\Carbon::parse($program->end_date) : null;

        if ($start && $end && $start->lt($end)) {
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $label = $cursor->format('Y-m');
                $financialSeries['labels'][] = $label;
                $financialSeries['planned'][] = round(($program->total_budget ?: 0) / max(1, $start->diffInMonths($end)), 2);
                $financialSeries['actual'][] = round(($program->spent_to_date ?: 0) / max(1, $start->diffInMonths($end)), 2);
                $cursor->addMonth();
            }
        }

        return view('portfolio.programs.show', compact('program','programMeta','projects','financialSeries'));
    }

    public function storeMilestone(Request $request, $programId)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);

        $data['program_id'] = $programId;
        $milestone = \App\Models\Milestone::create($data);

        return response()->json(['success' => true, 'milestone' => $milestone]);
    }

    public function updateMilestone(Request $request, $programId, $milestoneId)
    {
        $milestone = \App\Models\Milestone::where('program_id', $programId)->findOrFail($milestoneId);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'description' => 'nullable|string'
        ]);

        $milestone->update($data);

        return response()->json(['success' => true, 'milestone' => $milestone]);
    }

    public function destroyMilestone($programId, $milestoneId)
    {
        $milestone = \App\Models\Milestone::where('program_id', $programId)->findOrFail($milestoneId);
        $milestone->delete();
        return response()->json(['success' => true]);
    }

    public function exportCsv()
    {
        $programs = Program::with('projects')->get();
        $filename = 'programs_export_'.date('Ymd_His').'.csv';
        $columns = ['id','name','manager','status','project_count','total_budget','kdv_percent','kdv_amount','total_budget_kdv','total_spent','progress_percent'];

        return response()->streamDownload(function () use ($programs, $columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);

            foreach ($programs as $p) {
                $projects = $p->projects;
                $projectCount = $projects->count();
                $totalBudget = $projects->sum('budget');
                $totalSpent = $projects->sum('spent');
                $progress = $projects->avg('progress_percent') ?? 0;

                $kdvPercent = \App\Services\FinanceService::resolveKdvPercent(request()->get('kdv', null));
                $kdvAmount = \App\Services\FinanceService::kdvAmount((float)$totalBudget, $kdvPercent);
                $totalBudgetKdv = \App\Services\FinanceService::applyKdv((float)$totalBudget, $kdvPercent);

                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->manager,
                    $p->status,
                    $projectCount,
                    $totalBudget,
                    $kdvPercent,
                    $kdvAmount,
                    $totalBudgetKdv,
                    $totalSpent,
                    $progress
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
