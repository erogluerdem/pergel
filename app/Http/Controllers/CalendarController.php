<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;

class CalendarController extends Controller
{
    /**
     * Display calendar page
     */
    public function index(Request $request)
    {
        // Bu ay için takvim verileri
        $currentMonth = now()->format('Y-m');
        
        // Görevlerin deadline'ları
        $taskEvents = Task::with(['project', 'assignedTo'])
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now()->startOfMonth())
            ->where('deadline', '<=', now()->endOfMonth())
            ->get()
            ->map(function ($task) {
                return [
                    'id' => 'task-' . $task->id,
                    'title' => $task->title,
                    'start' => $task->deadline->format('Y-m-d'),
                    'end' => $task->deadline->format('Y-m-d'),
                    'className' => 'bg-' . $this->getTaskPriorityColor($task->priority),
                    'type' => 'task',
                    'description' => $task->description,
                    'project' => $task->project->name ?? null,
                    'assignee' => $task->assignedTo->name ?? null,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'url' => route('tasks.show', $task->id)
                ];
            });

        // Proje milestone'ları
        $projectEvents = Project::whereNotNull('milestone_date')
            ->where('milestone_date', '>=', now()->startOfMonth())
            ->where('milestone_date', '<=', now()->endOfMonth())
            ->get()
            ->map(function ($project) {
                return [
                    'id' => 'project-' . $project->id,
                    'title' => $project->name . ' (Milestone)',
                    'start' => $project->milestone_date->format('Y-m-d'),
                    'end' => $project->milestone_date->format('Y-m-d'),
                    'className' => 'bg-warning',
                    'type' => 'milestone',
                    'description' => $project->description,
                    'status' => $project->status,
                    'budget' => $project->budget,
                    'url' => route('projects.show', $project->id)
                ];
            });

        // Tüm etkinlikleri birleştir
        $events = $taskEvents->concat($projectEvents);

        // Güncel istatistikler
        $stats = [
            'this_month_deadlines' => Task::whereMonth('deadline', now()->month)
                ->whereYear('deadline', now()->year)
                ->where('status', '!=', 'completed')
                ->count(),
            'overdue_tasks' => Task::where('deadline', '<', now())
                ->where('status', '!=', 'completed')
                ->count(),
            'upcoming_milestones' => Project::where('milestone_date', '>', now())
                ->where('milestone_date', '<=', now()->addDays(30))
                ->count(),
            'completed_this_month' => Task::whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->where('status', 'completed')
                ->count()
        ];

        return view('calendar.index', compact('events', 'stats', 'currentMonth'));
    }

    /**
     * Get task priority color
     */
    private function getTaskPriorityColor($priority)
    {
        return match($priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            'medium' => 'info',
            'low' => 'success',
            default => 'primary'
        };
    }
}