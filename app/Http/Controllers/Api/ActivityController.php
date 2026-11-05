<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;

class ActivityController extends Controller
{
    /**
     * Get recent activities for the activities panel
     * Public endpoint - returns limited data if user is not authenticated
     */
    public function recent(Request $request)
    {
        try {
            // Check if user is authenticated
            $isAuthenticated = auth()->check();

            $activities = collect();

            // Only show detailed activities if user is authenticated
            if ($isAuthenticated) {
                // Son tamamlanan görevler
                $completedTasks = Task::with(['project'])
                    ->where('status', 'completed')
                    ->where('updated_at', '>=', now()->subDays(7))
                    ->orderBy('updated_at', 'desc')
                    ->limit(5)
                    ->get();

                foreach ($completedTasks as $task) {
                    $activities->push([
                        'type' => 'task_completed',
                        'title' => "Görev tamamlandı: {$task->title}",
                        'project_name' => $task->project->name ?? null,
                        'time_ago' => $task->updated_at->diffForHumans(),
                        'created_at' => $task->updated_at
                    ]);
                }

                // Yeni atanan görevler
                $assignedTasks = Task::with(['project', 'assignedTo'])
                    ->where('status', 'assigned')
                    ->where('created_at', '>=', now()->subDays(3))
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                foreach ($assignedTasks as $task) {
                    $activities->push([
                        'type' => 'task_assigned',
                        'title' => "Yeni görev atandı: {$task->title}",
                        'project_name' => $task->project->name ?? null,
                        'time_ago' => $task->created_at->diffForHumans(),
                        'created_at' => $task->created_at
                    ]);
                }

                // Yeni projeler
                $newProjects = Project::where('created_at', '>=', now()->subDays(7))
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();

                foreach ($newProjects as $project) {
                    $activities->push([
                        'type' => 'project_created',
                        'title' => "Yeni proje oluşturuldu: {$project->name}",
                        'project_name' => null,
                        'time_ago' => $project->created_at->diffForHumans(),
                        'created_at' => $project->created_at
                    ]);
                }

                // Yaklaşan deadline'lar
                $approachingTasks = Task::with(['project'])
                    ->where('due_date', '>=', now())
                    ->where('due_date', '<=', now()->addDays(3))
                    ->where('status', '!=', 'completed')
                    ->orderBy('due_date', 'asc')
                    ->limit(5)
                    ->get();

                foreach ($approachingTasks as $task) {
                    $activities->push([
                        'type' => 'deadline_approaching',
                        'title' => "Deadline yaklaşıyor: {$task->title}",
                        'project_name' => $task->project->name ?? null,
                        'time_ago' => $task->due_date ? $task->due_date->diffForHumans() : 'Tarih belirtilmemiş',
                        'created_at' => $task->due_date ?? now()
                    ]);
                }

                // Geciken görevler
                $overdueTasks = Task::with(['project'])
                    ->where('due_date', '<', now())
                    ->where('status', '!=', 'completed')
                    ->orderBy('due_date', 'desc')
                    ->limit(3)
                    ->get();

                foreach ($overdueTasks as $task) {
                    $activities->push([
                        'type' => 'overdue',
                        'title' => "Geciken görev: {$task->title}",
                        'project_name' => $task->project->name ?? null,
                        'time_ago' => $task->due_date ? $task->due_date->diffForHumans() : 'Tarih belirtilmemiş',
                        'created_at' => $task->due_date ?? now()
                    ]);
                }
            }

            // Aktiviteleri tarihe göre sırala ve sınırla
            $sortedActivities = $activities->sortByDesc('created_at')->take(10)->values();

            // Proje özet bilgilerini de ekle
            if ($isAuthenticated) {
                $summary = [
                    'active_projects' => Project::where('status', 'active')->count(),
                    'pending_tasks' => Task::where('status', 'pending')->count(),
                    'overdue_tasks' => Task::where('due_date', '<', now())
                        ->where('status', '!=', 'completed')->count(),
                    'active_users' => User::count(),
                ];
            } else {
                // Limited summary for non-authenticated users
                $summary = [
                    'active_projects' => Project::where('status', 'active')->count(),
                    'pending_tasks' => 0,
                    'overdue_tasks' => 0,
                    'active_users' => 0,
                ];
            }

            return response()->json([
                'success' => true,
                'activities' => $sortedActivities,
                'summary' => $summary,
                'authenticated' => $isAuthenticated
            ]);

        } catch (\Exception $e) {
            // Log error and always return JSON to avoid HTML error pages breaking frontend JSON.parse
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Aktiviteler yüklenirken hata oluştu',
                'error' => config('app.debug') ? $e->getMessage() : 'Bir hata oluştu'
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Get upcoming activities
     */
    public function upcoming(Request $request)
    {
        try {
            $upcomingActivities = collect();

            // Bugünün görevleri
            $todayTasks = Task::with(['project', 'assignedTo'])
                ->whereDate('due_date', today())
                ->where('status', '!=', 'completed')
                ->orderBy('due_date', 'asc')
                ->get();

            foreach ($todayTasks as $task) {
                $upcomingActivities->push([
                    'time' => $task->due_date ? $task->due_date->format('H:i') : '09:00',
                    'title' => "Görev: {$task->title}",
                    'description' => $task->project->name ?? 'Proje belirtilmemiş',
                    'priority' => $task->priority ?? 'normal',
                    'type' => 'task'
                ]);
            }

            // Yaklaşan milestone'lar
            $milestones = Project::where('milestone_date', '>=', now())
                ->where('milestone_date', '<=', now()->addDays(7))
                ->orderBy('milestone_date', 'asc')
                ->get();

            foreach ($milestones as $project) {
                if ($project->milestone_date) {
                    $upcomingActivities->push([
                        'time' => $project->milestone_date->format('H:i'),
                        'title' => "Milestone: {$project->name}",
                        'description' => 'Proje kilometre taşı',
                        'priority' => 'high',
                        'type' => 'milestone'
                    ]);
                }
            }

            // Tarihe göre sırala
            $sortedActivities = $upcomingActivities->sortBy('time')->take(8)->values();

            return response()->json([
                'success' => true,
                'activities' => $sortedActivities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Yaklaşan aktiviteler yüklenirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get project summary for dashboard
     */
    public function summary(Request $request)
    {
        try {
            $summary = [
                // Proje istatistikleri
                'projects' => [
                    'total' => Project::count(),
                    'active' => Project::where('status', 'active')->count(),
                    'completed' => Project::where('status', 'completed')->count(),
                    'pending' => Project::where('status', 'pending')->count(),
                ],

                // Görev istatistikleri
                'tasks' => [
                    'total' => Task::count(),
                    'pending' => Task::where('status', 'pending')->count(),
                    'in_progress' => Task::where('status', 'in_progress')->count(),
                    'completed' => Task::where('status', 'completed')->count(),
                    'overdue' => Task::where('due_date', '<', now())
                        ->where('status', '!=', 'completed')->count(),
                ],

                // Kullanıcı istatistikleri
                'users' => [
                    'total' => User::count(),
                    'active_today' => 0, // last_activity alanı yoksa
                    'active_week' => 0, // last_activity alanı yoksa
                ],

                // Bu haftaki aktivite özeti
                'weekly_activity' => [
                    'completed_tasks' => Task::where('status', 'completed')
                        ->where('updated_at', '>=', now()->startOfWeek())->count(),
                    'created_projects' => Project::where('created_at', '>=', now()->startOfWeek())->count(),
                    'new_users' => User::where('created_at', '>=', now()->startOfWeek())->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'summary' => $summary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Özet bilgiler yüklenirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
