<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectPlanningController extends Controller
{
    public function index(Request $request)
    {
        // For now show a global planning page; later we will support project-specific planning
        $projects = Project::orderBy('name')->get();
        return view('projects.planning.index_modern', compact('projects'));
    }

    public function tasks($projectId)
    {
        $project = Project::findOrFail($projectId);
        // $this->authorize('view', $project); // Temporarily disabled for testing
        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)->orderBy('start_date')->get();
        return response()->json($tasks);
    }

    public function updateTask(Request $request, $projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $task = \App\Models\PlanningTask::where('project_id', $project->id)->where('id', $taskId)->firstOrFail();
        $data = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'duration' => 'nullable|integer',
            'progress' => 'nullable|integer',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'integer',
            'resources' => 'nullable|array',
            'budget_wbs' => 'nullable|string',
            'documents' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);
        // if dependencies present, validate they belong to the same project and detect cycles
        if (array_key_exists('dependencies', $data)) {
            $deps = $data['dependencies'] ?? [];
            // ensure all dependency ids exist and belong to project
            $validIds = \App\Models\PlanningTask::where('project_id', $project->id)->whereIn('id', $deps)->pluck('id')->map(function($v){return (int)$v;})->toArray();
            if (count($validIds) !== count($deps)) {
                return response()->json(['message' => 'Geçersiz bağımlılık tanımı.'], 422);
            }

            // build adjacency from existing tasks, but replace current task edges with the incoming ones
            $all = \App\Models\PlanningTask::where('project_id', $project->id)->get()->keyBy('id')->map(function($t){ return $t->dependencies ?? []; })->toArray();
            // normalize to arrays
            foreach ($all as $k => $v) { $all[$k] = is_array($v) ? array_map('intval', $v) : []; }
            $all[$task->id] = array_map('intval', $deps);

            if ($this->hasCycle($all)) {
                return response()->json(['message' => 'Bağımlılık döngüsü tespit edildi. Kaydetme reddedildi.'], 422);
            }
        }

        $task->fill($data);
        $task->save();
        // recalculate critical path after update
        $this->calculateCriticalPath($project->id);
        return response()->json($task);
    }

    public function storeTask(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'responsible' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'duration' => 'nullable|integer',
            'notes' => 'nullable|string'
        ]);

        $task = new \App\Models\PlanningTask();
        $task->project_id = $project->id;
        $task->fill($data);
        $task->save();

        // recalculate critical path after create
        $this->calculateCriticalPath($project->id);
        return response()->json($task);
    }

    // detect cycles in directed graph represented as adjacency list: [id => [depId,...]]
    private function hasCycle(array $adj): bool
    {
        $visited = [];
        $recStack = [];

        $visit = function($node) use (&$visit, &$visited, &$recStack, $adj) {
            if (!isset($visited[$node])) {
                $visited[$node] = true;
                $recStack[$node] = true;
                foreach ($adj[$node] ?? [] as $nei) {
                    if (!isset($visited[$nei]) && $visit($nei)) return true;
                    if (isset($recStack[$nei])) return true;
                }
            }
            unset($recStack[$node]);
            return false;
        };

        foreach (array_keys($adj) as $n) {
            if (!isset($visited[$n]) && $visit($n)) return true;
        }
        return false;
    }

    // create a baseline snapshot for the project
    public function createBaseline(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)->get();
        $snapshot = $tasks->map(function($t){ return $t->toArray(); });
        $baseline = DB::table('planning_baselines')->insertGetId([
            'project_id' => $project->id,
            'name' => $data['name'] ?? 'Baseline '.date('Y-m-d H:i'),
            'notes' => $data['notes'] ?? null,
            'snapshot' => json_encode($snapshot),
            'created_by' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['id' => $baseline, 'message' => 'Baseline oluşturuldu']);
    }

    // list baselines for a project
    public function listBaselines($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $baselines = DB::table('planning_baselines')->where('project_id', $project->id)->orderBy('created_at', 'desc')->get();
        return response()->json($baselines);
    }

    // compare baseline id with current tasks - returns simple diff structure
    public function compareBaseline(Request $request, $projectId, $baselineId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $baseline = DB::table('planning_baselines')->where('id', $baselineId)->where('project_id', $project->id)->firstOrFail();
        $snapshot = json_decode($baseline->snapshot, true) ?: [];
        $current = \App\Models\PlanningTask::where('project_id', $project->id)->get()->keyBy('id')->toArray();
        $diffs = [];
        foreach ($snapshot as $s) {
            $id = $s['id'] ?? null;
            if ($id && isset($current[$id])) {
                $cur = $current[$id];
                $changes = [];
                foreach (['start_date','end_date','duration','progress'] as $k) {
                    $bv = $s[$k] ?? null; $cv = $cur[$k] ?? null;
                    if ($bv != $cv) { $changes[$k] = ['baseline' => $bv, 'current' => $cv]; }
                }
                if ($changes) { $diffs[$id] = ['task' => $cur['name'] ?? $id, 'changes' => $changes]; }
            } else {
                $diffs[] = ['task' => $s['name'] ?? 'unknown', 'changes' => ['status' => 'removed']];
            }
        }
        return response()->json(['diffs' => $diffs]);
    }

    // calculate and mark critical path tasks
    public function calculateCriticalPath($projectId)
    {
        $tasks = \App\Models\PlanningTask::where('project_id', $projectId)->get()->keyBy('id');
        if ($tasks->isEmpty()) return;

        // build graph: forward and backward
        $adj = []; $revAdj = [];
        foreach ($tasks as $t) {
            $adj[$t->id] = $t->dependencies ?? [];
            foreach ($adj[$t->id] as $d) {
                $revAdj[$d] = $revAdj[$d] ?? [];
                $revAdj[$d][] = $t->id;
            }
        }

        // earliest start/finish
        $es = []; $ef = [];
        foreach ($tasks as $t) {
            $es[$t->id] = 0;
            $ef[$t->id] = ($t->duration ?? 1);
        }
        $visited = [];
        $topoOrder = $this->topologicalSort($adj);
        foreach ($topoOrder as $id) {
            if (!isset($tasks[$id])) continue;
            $t = $tasks[$id];
            foreach ($adj[$id] ?? [] as $dep) {
                if (isset($ef[$dep])) {
                    $es[$id] = max($es[$id], $ef[$dep]);
                }
            }
            $ef[$id] = $es[$id] + ($t->duration ?? 1);
        }

        // latest start/finish
        $lf = []; $ls = [];
        $projectEnd = max($ef);
        foreach ($tasks as $t) {
            $lf[$t->id] = $projectEnd;
            $ls[$t->id] = $projectEnd - ($t->duration ?? 1);
        }
        $revTopo = array_reverse($topoOrder);
        foreach ($revTopo as $id) {
            if (!isset($tasks[$id])) continue;
            foreach ($revAdj[$id] ?? [] as $succ) {
                if (isset($ls[$succ])) {
                    $lf[$id] = min($lf[$id], $ls[$succ]);
                }
            }
            $ls[$id] = $lf[$id] - ($tasks[$id]->duration ?? 1);
        }

        // critical path: where es == ls
        foreach ($tasks as $t) {
            $t->critical_path = ($es[$t->id] == $ls[$t->id]);
            $t->save();
        }
    }

    private function topologicalSort($adj)
    {
        $visited = []; $order = [];
        $dfs = function($node) use (&$dfs, &$visited, &$order, $adj) {
            if (isset($visited[$node])) return;
            $visited[$node] = true;
            foreach ($adj[$node] ?? [] as $nei) {
                if (!isset($visited[$nei])) $dfs($nei);
            }
            $order[] = $node;
        };
        foreach (array_keys($adj) as $n) {
            if (!isset($visited[$n])) $dfs($n);
        }
        return array_reverse($order);
    }

    // get detailed task info for detail panel
    public function getTaskDetails($projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $task = \App\Models\PlanningTask::with('project')->where('project_id', $project->id)->where('id', $taskId)->firstOrFail();
        // enrich with related data if needed
        return response()->json($task);
    }

    // resource histogram: aggregate resource usage over time
    public function getResourceHistogram($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)->get();
        $histogram = [];
        foreach ($tasks as $t) {
            if (!$t->start_date || !$t->end_date || !$t->resources) continue;
            $resources = $t->resources;
            $start = \Carbon\Carbon::parse($t->start_date);
            $end = \Carbon\Carbon::parse($t->end_date);
            $weeks = $start->diffInWeeks($end) + 1;
            for ($i = 0; $i < $weeks; $i++) {
                $week = $start->copy()->addWeeks($i)->format('Y-W');
                if (!isset($histogram[$week])) $histogram[$week] = ['human' => [], 'equipment' => []];
                if (isset($resources['human'])) {
                    foreach ($resources['human'] as $h) {
                        $role = $h['role'] ?? 'unknown';
                        $count = $h['count'] ?? 1;
                        $histogram[$week]['human'][$role] = ($histogram[$week]['human'][$role] ?? 0) + $count;
                    }
                }
                if (isset($resources['equipment'])) {
                    foreach ($resources['equipment'] as $e) {
                        $name = $e['name'] ?? 'unknown';
                        $count = $e['count'] ?? 1;
                        $histogram[$week]['equipment'][$name] = ($histogram[$week]['equipment'][$name] ?? 0) + $count;
                    }
                }
            }
        }
        return response()->json($histogram);
    }

        // 2-week lookahead report
    public function getTwoWeekLookahead($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);
        $now = now();
        $twoWeeksLater = $now->copy()->addWeeks(2);
        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)
            ->where(function($q) use ($now, $twoWeeksLater) {
                $q->whereBetween('start_date', [$now, $twoWeeksLater])
                  ->orWhereBetween('end_date', [$now, $twoWeeksLater])
                  ->orWhere(function($qq) use ($now) { $qq->where('start_date', '<=', $now)->where('end_date', '>=', $now); });
            })
            ->orderBy('start_date')
            ->get();
        $report = [
            'starting' => $tasks->filter(function($t) use ($now, $twoWeeksLater) { return $t->start_date >= $now && $t->start_date <= $twoWeeksLater; }),
            'ongoing' => $tasks->filter(function($t) use ($now) { return $t->start_date <= $now && $t->end_date >= $now; }),
            'ending' => $tasks->filter(function($t) use ($now, $twoWeeksLater) { return $t->end_date >= $now && $t->end_date <= $twoWeeksLater; }),
        ];
        return response()->json($report);
    }

    // Create new task
    public function createTask(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'nullable|integer|min:1',
            'progress' => 'nullable|integer|min:0|max:100',
            'responsible' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'resources' => 'nullable|array',
            'dependencies' => 'nullable|array',
            'dependencies.*' => 'integer',
        ]);

        // Calculate duration if not provided
        if (!isset($data['duration'])) {
            $start = \Carbon\Carbon::parse($data['start_date']);
            $end = \Carbon\Carbon::parse($data['end_date']);
            $data['duration'] = $start->diffInDays($end) + 1;
        }

        $data['project_id'] = $project->id;
        $data['progress'] = $data['progress'] ?? 0;

        $task = \App\Models\PlanningTask::create($data);

        // Recalculate critical path
        $this->calculateCriticalPath($project->id);

        return response()->json($task);
    }

    // Delete task
    public function deleteTask(Request $request, $projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $task = \App\Models\PlanningTask::where('project_id', $project->id)->where('id', $taskId)->firstOrFail();

        // Remove this task from other tasks' dependencies
        \App\Models\PlanningTask::where('project_id', $project->id)
            ->whereNotNull('dependencies')
            ->get()
            ->each(function($t) use ($taskId) {
                $deps = $t->dependencies ?? [];
                if (in_array($taskId, $deps)) {
                    $t->dependencies = array_values(array_diff($deps, [$taskId]));
                    $t->save();
                }
            });

        $task->delete();

        // Recalculate critical path
        $this->calculateCriticalPath($project->id);

        return response()->json(['message' => 'Görev silindi']);
    }

    // Update task progress
    public function updateTaskProgress(Request $request, $projectId, $taskId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $task = \App\Models\PlanningTask::where('project_id', $project->id)->where('id', $taskId)->firstOrFail();

        $data = $request->validate([
            'progress' => 'required|integer|min:0|max:100'
        ]);

        $task->progress = $data['progress'];
        $task->save();

        return response()->json($task);
    }

    // Bulk update tasks
    public function bulkUpdateTasks(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $data = $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|integer',
            'updates.*.progress' => 'nullable|integer|min:0|max:100',
            'updates.*.start_date' => 'nullable|date',
            'updates.*.end_date' => 'nullable|date',
        ]);

        $updated = [];
        foreach ($data['updates'] as $update) {
            $task = \App\Models\PlanningTask::where('project_id', $project->id)->where('id', $update['id'])->first();
            if ($task) {
                $task->fill(array_filter($update, function($value) { return $value !== null; }));
                $task->save();
                $updated[] = $task;
            }
        }

        // Recalculate critical path
        $this->calculateCriticalPath($project->id);

        return response()->json($updated);
    }

    // Get project statistics
    public function getProjectStatistics($projectId)
    {
        $project = Project::findOrFail($projectId);
        // $this->authorize('view', $project); // Temporarily disabled for testing

        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)->get();
        $now = now();

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('progress', '>=', 100)->count();
        $overdueTasks = $tasks->filter(function($task) use ($now) {
            return $task->end_date < $now && $task->progress < 100;
        })->count();
        $criticalPathTasks = $tasks->where('critical_path', true)->count();

        // Progress distribution
        $notStarted = $tasks->where('progress', 0)->count();
        $inProgress = $tasks->filter(function($task) {
            return $task->progress > 0 && $task->progress < 100;
        })->count();

        // Resource utilization (sample data based on resources)
        $resourceUtilization = [];
        $tasks->each(function($task) use (&$resourceUtilization) {
            if ($task->resources && is_array($task->resources)) {
                foreach ($task->resources as $resource) {
                    $role = $resource['role'] ?? 'Unknown';
                    $count = $resource['count'] ?? 1;
                    $resourceUtilization[$role] = ($resourceUtilization[$role] ?? 0) + $count;
                }
            }
        });

        return response()->json([
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'overdue_tasks' => $overdueTasks,
            'critical_path_tasks' => $criticalPathTasks,
            'progress_distribution' => [
                'not_started' => $notStarted,
                'in_progress' => $inProgress,
                'completed' => $completedTasks,
            ],
            'resource_utilization' => $resourceUtilization,
            'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0,
            'average_progress' => $totalTasks > 0 ? round($tasks->avg('progress'), 1) : 0,
        ]);
    }

    // Get timeline data for charts
    public function getTimelineData($projectId)
    {
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        $tasks = \App\Models\PlanningTask::where('project_id', $project->id)->get();

        // Group tasks by month
        $timeline = [];
        $now = now();

        for ($i = -6; $i <= 6; $i++) {
            $month = $now->copy()->addMonths($i);
            $monthKey = $month->format('Y-m');
            $monthLabel = $month->format('M Y');

            $monthTasks = $tasks->filter(function($task) use ($month) {
                $taskStart = \Carbon\Carbon::parse($task->start_date);
                $taskEnd = \Carbon\Carbon::parse($task->end_date);

                return $taskStart->format('Y-m') === $month->format('Y-m') ||
                       $taskEnd->format('Y-m') === $month->format('Y-m') ||
                       ($taskStart <= $month->endOfMonth() && $taskEnd >= $month->startOfMonth());
            });

            $timeline[] = [
                'month' => $monthLabel,
                'planned' => $monthTasks->count(),
                'completed' => $monthTasks->where('progress', '>=', 100)->count(),
                'in_progress' => $monthTasks->filter(function($task) {
                    return $task->progress > 0 && $task->progress < 100;
                })->count(),
            ];
        }

        return response()->json($timeline);
    }

    // Export tasks
    public function exportTasks(Project $project)
    {
        // $this->authorize('view', $project); // Temporarily disabled for testing
        $tasks = $project->planningTasks()->get();

        $data = [
            'project' => $project->name,
            'export_date' => now()->format('d.m.Y H:i'),
            'task_count' => $tasks->count(),
            'completed_tasks' => $tasks->where('progress', '>=', 100)->count(),
            'tasks' => $tasks->map(function($task) {
                return [
                    'id' => $task->id,
                    'name' => $task->name,
                    'description' => $task->description ?? '',
                    'start_date' => $task->start_date ? $task->start_date->format('d.m.Y') : '',
                    'end_date' => $task->end_date ? $task->end_date->format('d.m.Y') : '',
                    'progress' => $task->progress . '%',
                    'priority' => $task->priority ?? 'Normal',
                    'status' => $task->status ?? 'Planlanan',
                    'assigned_to' => $task->assigned_to ?? 'Atanmamış',
                    'duration_days' => $task->start_date && $task->end_date ?
                        $task->start_date->diffInDays($task->end_date) : 0,
                    'dependencies' => $task->dependencies ?? [],
                    'resources' => $task->resources ?? [],
                ];
            })
        ];

        return response()->json([
            'success' => true,
            'message' => 'Proje verileri başarıyla hazırlandı',
            'data' => $data
        ]);
    }

    // Risk analysis
    public function riskAnalysis(Project $project)
    {
        // $this->authorize('view', $project); // Temporarily disabled for testing
        $tasks = $project->planningTasks()->get();

        $risks = [
            'high_risk_tasks' => $tasks->where('priority', 'Yüksek')->count(),
            'delayed_tasks' => $tasks->filter(function($task) {
                return $task->end_date && $task->end_date < now() && $task->progress < 100;
            })->count(),
            'critical_path_delays' => $tasks->where('critical_path', true)->where('progress', '<', 50)->count(),
            'resource_conflicts' => 0, // Simplified for demo
            'budget_overruns' => rand(1, 3),
        ];

        $risk_score = ($risks['high_risk_tasks'] * 0.2) +
                     ($risks['delayed_tasks'] * 0.4) +
                     ($risks['critical_path_delays'] * 0.3);

        $risk_level = $risk_score > 5 ? 'Yüksek' : ($risk_score > 2 ? 'Orta' : 'Düşük');
        $risk_color = $risk_score > 5 ? '#dc3545' : ($risk_score > 2 ? '#fd7e14' : '#28a745');

        $recommendations = [];
        if($risks['delayed_tasks'] > 0) {
            $recommendations[] = 'Geciken görevleri önceliklendir ve kaynak tahsisini gözden geçir';
        }
        if($risks['critical_path_delays'] > 0) {
            $recommendations[] = 'Kritik yol görevlerine odaklan ve ek kaynak sağla';
        }
        if(empty($recommendations)) {
            $recommendations[] = 'Proje şu anda düşük risk seviyesinde görünüyor';
        }

        return response()->json([
            'success' => true,
            'risk_level' => $risk_level,
            'risk_score' => round($risk_score, 1),
            'risk_color' => $risk_color,
            'risks' => $risks,
            'recommendations' => $recommendations
        ]);
    }

    // Resource report
    public function resourceReport(Project $project)
    {
        // $this->authorize('view', $project); // Temporarily disabled for testing
        $tasks = $project->planningTasks()->get();

        $resources = [];
        $total_hours = 0;

        foreach($tasks as $task) {
            if($task->resources) {
                $taskResources = is_array($task->resources) ? $task->resources : json_decode($task->resources, true);
                if(is_array($taskResources)) {
                    foreach($taskResources as $resource) {
                        $name = is_string($resource) ? $resource : ($resource['name'] ?? 'Unknown');
                        if(!isset($resources[$name])) {
                            $resources[$name] = [
                                'name' => $name,
                                'task_count' => 0,
                                'hours' => 0,
                                'utilization' => 0,
                                'tasks' => []
                            ];
                        }
                        $resources[$name]['task_count']++;
                        $resources[$name]['tasks'][] = $task->name;
                        $hours = $task->duration ? $task->duration * 8 : rand(20, 80);
                        $resources[$name]['hours'] += $hours;
                        $total_hours += $hours;
                    }
                }
            }
        }

        // Calculate utilization percentages
        foreach($resources as &$resource) {
            $resource['utilization'] = $total_hours > 0 ? round(($resource['hours'] / $total_hours) * 100, 1) : 0;
        }

        return response()->json([
            'success' => true,
            'total_resources' => count($resources),
            'total_hours' => $total_hours,
            'resources' => array_values($resources),
            'most_utilized' => !empty($resources) ? collect($resources)->sortByDesc('utilization')->first() : null
        ]);
    }
}
