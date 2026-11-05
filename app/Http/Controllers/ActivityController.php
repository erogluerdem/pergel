<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class ActivityController extends Controller
{
    /**
     * Display activities page
     */
    public function index(Request $request)
    {
        // Günün aktiviteleri
        $todayActivities = collect();

        // Bugün tamamlanan görevler
        $todayCompletedTasks = Task::with(['project', 'assignedTo'])
            ->whereDate('updated_at', today())
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($todayCompletedTasks as $task) {
            $todayActivities->push([
                'type' => 'task_completed',
                'title' => "Görev Tamamlandı",
                'description' => $task->title,
                'project' => $task->project->name ?? 'Belirsiz',
                'user' => $task->assignedTo->name ?? 'Belirsiz',
                'time' => $task->updated_at->format('H:i'),
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
                'created_at' => $task->updated_at
            ]);
        }

        // Bugün oluşturulan projeler
        $todayProjects = Project::whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($todayProjects as $project) {
            $todayActivities->push([
                'type' => 'project_created',
                'title' => "Yeni Proje",
                'description' => $project->name,
                'project' => $project->name,
                'user' => $project->manager->name ?? 'Belirsiz',
                'time' => $project->created_at->format('H:i'),
                'icon' => 'fas fa-project-diagram',
                'color' => 'info',
                'created_at' => $project->created_at
            ]);
        }

        // Bugün atanan görevler (defansif: sütun yoksa atla)
        $todayAssignedTasks = collect();
        if (Schema::hasColumn('tasks', 'assigned_at')) {
            $todayAssignedTasks = Task::with(['project', 'assignedTo'])
                ->whereDate('assigned_at', today())
                ->where('status', 'assigned')
                ->orderBy('assigned_at', 'desc')
                ->get();

            foreach ($todayAssignedTasks as $task) {
                $todayActivities->push([
                    'type' => 'task_assigned',
                    'title' => "Görev Atandı",
                    'description' => $task->title,
                    'project' => $task->project->name ?? 'Belirsiz',
                    'user' => $task->assignedTo->name ?? 'Belirsiz',
                    'time' => $task->assigned_at ? $task->assigned_at->format('H:i') : $task->created_at->format('H:i'),
                    'icon' => 'fas fa-tasks',
                    'color' => 'primary',
                    'created_at' => $task->assigned_at ?? $task->created_at
                ]);
            }
        }

        // Aktiviteleri tarihe göre sırala
        $todayActivities = $todayActivities->sortByDesc('created_at');

        // Bu haftaki aktiviteler
        $weekActivities = collect();

        // Bu hafta tamamlanan görevler
        $weekCompletedTasks = Task::with(['project', 'assignedTo'])
            ->whereBetween('updated_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'completed')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($weekCompletedTasks as $task) {
            $weekActivities->push([
                'type' => 'task_completed',
                'title' => "Görev Tamamlandı",
                'description' => $task->title,
                'project' => $task->project->name ?? 'Belirsiz',
                'user' => $task->assignedTo->name ?? 'Belirsiz',
                'date' => $task->updated_at->format('d.m.Y'),
                'time' => $task->updated_at->format('H:i'),
                'icon' => 'fas fa-check-circle',
                'color' => 'success',
                'created_at' => $task->updated_at
            ]);
        }

        // Bu hafta oluşturulan projeler
        $weekProjects = Project::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($weekProjects as $project) {
            $weekActivities->push([
                'type' => 'project_created',
                'title' => "Yeni Proje",
                'description' => $project->name,
                'project' => $project->name,
                'user' => $project->manager->name ?? 'Belirsiz',
                'date' => $project->created_at->format('d.m.Y'),
                'time' => $project->created_at->format('H:i'),
                'icon' => 'fas fa-project-diagram',
                'color' => 'info',
                'created_at' => $project->created_at
            ]);
        }

        // Aktiviteleri tarihe göre sırala
        $weekActivities = $weekActivities->sortByDesc('created_at');

        // İstatistikler
        $stats = [
            'today_completed_tasks' => $todayCompletedTasks->count(),
            'today_created_projects' => $todayProjects->count(),
            'today_assigned_tasks' => $todayAssignedTasks->count(),
            'total_activities_today' => $todayActivities->count(),

            'week_completed_tasks' => $weekCompletedTasks->count(),
            'week_created_projects' => $weekProjects->count(),
            'total_activities_week' => $weekActivities->count(),

            'active_projects' => Project::where('status', 'active')->count(),
            'pending_tasks' => Task::where('status', 'pending')->count(),
            'overdue_tasks' => Schema::hasColumn('tasks', 'deadline')
                ? Task::where('deadline', '<', now())->where('status', '!=', 'completed')->count()
                : 0,
        ];

        return view('activities.index', compact('todayActivities', 'weekActivities', 'stats'));
    }
}
