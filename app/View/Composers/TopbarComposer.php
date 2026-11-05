<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TopbarComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $data = [
            // Proje istatistikleri
            'activeProjectsCount' => Project::where('status', 'active')->count(),
            'pendingTasksCount' => Task::where('status', 'pending')->count(),
            'overdueTasksCount' => Task::where('due_date', '<', now())
                ->where('status', '!=', 'completed')->count(),
            'activeUsersCount' => $this->getActiveUsersCount(),

            // Son aktiviteler (Activities panel için)
            'recentActivities' => $this->getRecentActivities(),

            // Yaklaşan aktiviteler
            'upcomingActivities' => $this->getUpcomingActivities(),

            // Bildirim istatistikleri (zaten mevcut ama ek kontrol)
            'notificationStats' => $this->getNotificationStats(),
            'userNotifications' => $this->getUserNotifications(),
            // Message & task counters used by topbar badges
            'messageCount' => $this->getMessageCount(),
            'tasksCount' => $this->getUserTasksCount(),
        ];

        $view->with($data);
    }

    /**
     * Kullanıcının okunmamış mesaj sayısı - Message model yoksa 0 döndür
     */
    private function getMessageCount(): int
    {
        try {
            if (class_exists(\App\Models\Message::class)) {
                return \App\Models\Message::where('user_id', auth()->id())->where('is_read', false)->count();
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return 0;
    }

    /**
     * Kullanıcının atanmış açık görev sayısı
     */
    private function getUserTasksCount(): int
    {
        try {
            return \App\Models\Task::where('assigned_to', auth()->id())->where('status', '!=', 'completed')->count();
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Son aktiviteleri getir
     */
    private function getRecentActivities(): Collection
    {
        $activities = collect();

        // Son tamamlanan görevler
        $completedTasks = Task::with(['project', 'assignedTo'])
            ->where('status', 'completed')
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($completedTasks as $task) {
            $activities->push([
                'type' => 'task_completed',
                'title' => "Görev tamamlandı: " . $task->title,
                'project_name' => $task->project->name ?? 'Belirsiz',
                'time' => $task->updated_at->diffForHumans(),
                'user' => $task->assignedTo->name ?? 'Belirsiz',
                'created_at' => $task->updated_at
            ]);
        }

        // Yeni atanan görevler (pending veya in_progress status'u olanlar)
        $assignedTasks = Task::with(['project', 'assignedTo'])
            ->whereIn('status', ['pending', 'in_progress', 'assigned'])
            ->where('created_at', '>=', now()->subDays(3))
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        foreach ($assignedTasks as $task) {
            $activities->push([
                'type' => 'task_assigned',
                'title' => "Yeni görev atandı: " . $task->title,
                'project_name' => $task->project->name ?? 'Belirsiz',
                'time' => $task->created_at->diffForHumans(),
                'user' => $task->assignedTo->name ?? 'Belirsiz',
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
                'title' => "Yeni proje oluşturuldu: " . $project->name,
                'project_name' => null,
                'time' => $project->created_at->diffForHumans(),
                'user' => $project->manager->name ?? 'Sistem',
                'created_at' => $project->created_at
            ]);
        }

        // Yaklaşan deadline'lar
        $approachingTasks = Task::with(['project', 'assignedTo'])
            ->where('due_date', '>=', now())
            ->where('due_date', '<=', now()->addDays(3))
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(3)
            ->get();

        foreach ($approachingTasks as $task) {
            $activities->push([
                'type' => 'deadline_approaching',
                'title' => "Deadline yaklaşıyor: " . $task->title,
                'project_name' => $task->project->name ?? 'Belirsiz',
                'time' => $task->due_date->diffForHumans(),
                'user' => $task->assignedTo->name ?? 'Belirsiz',
                'created_at' => $task->due_date
            ]);
        }

        // Geciken görevler
        $overdueTasks = Task::with(['project', 'assignedTo'])
            ->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'desc')
            ->limit(2)
            ->get();

        foreach ($overdueTasks as $task) {
            $activities->push([
                'type' => 'overdue',
                'title' => "Geciken görev: " . $task->title,
                'project_name' => $task->project->name ?? 'Belirsiz',
                'time' => $task->due_date->diffForHumans(),
                'user' => $task->assignedTo->name ?? 'Belirsiz',
                'created_at' => $task->due_date
            ]);
        }

        // Aktiviteleri tarihe göre sırala ve sınırla
        return $activities->sortByDesc('created_at')->take(8)->values();
    }

    /**
     * Yaklaşan aktiviteleri getir
     */
    private function getUpcomingActivities(): Collection
    {
        $upcomingActivities = collect();

        // Bugünün görevleri
        $todayTasks = Task::with(['project', 'assignedTo'])
            ->whereDate('due_date', today())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        foreach ($todayTasks as $task) {
            $upcomingActivities->push([
                'time' => $task->due_date ? $task->due_date->format('H:i') : '09:00',
                'title' => "Görev: " . $task->title,
                'description' => $task->project->name ?? 'Proje belirtilmemiş',
                'priority' => $task->priority ?? 'normal',
                'type' => 'task',
                'participants' => $task->assignedTo ? [
                    [
                        'name' => $task->assignedTo->name,
                        'avatar' => $task->assignedTo->avatar ?? null
                    ]
                ] : []
            ]);
        }

        // Yaklaşan milestone'lar (eğer varsa)
        try {
            $milestones = Project::whereNotNull('milestone_date')
                ->where('milestone_date', '>=', now())
                ->where('milestone_date', '<=', now()->addDays(7))
                ->orderBy('milestone_date', 'asc')
                ->limit(3)
                ->get();

            foreach ($milestones as $project) {
                $upcomingActivities->push([
                    'time' => $project->milestone_date->format('H:i'),
                    'title' => "Milestone: " . $project->name,
                    'description' => 'Proje kilometre taşı',
                    'priority' => 'high',
                    'type' => 'milestone',
                    'participants' => []
                ]);
            }
        } catch (\Exception $e) {
            // milestone_date kolonu yoksa sessizce devam et
        }

        return $upcomingActivities->sortBy('time')->take(6)->values();
    }

    /**
     * Aktif kullanıcı sayısını getir
     */
    private function getActiveUsersCount(): int
    {
        try {
            // last_activity field'i varsa onu kullan
            return User::where('last_activity', '>=', now()->subDays(7))->count();
        } catch (\Exception $e) {
            try {
                // last_activity yoksa updated_at kullan
                return User::where('updated_at', '>=', now()->subDays(7))->count();
            } catch (\Exception $e2) {
                // Hiçbiri yoksa toplam kullanıcı sayısını döndür
                return User::count();
            }
        }
    }

    /**
     * Bildirim istatistiklerini getir
     */
    private function getNotificationStats(): array
    {
        try {
            $unreadCount = Notification::where('user_id', auth()->id())
                ->where('is_read', false)
                ->count();

            return [
                'unread' => $unreadCount,
                'total' => Notification::where('user_id', auth()->id())->count()
            ];
        } catch (\Exception $e) {
            return ['unread' => 0, 'total' => 0];
        }
    }

    /**
     * Kullanıcı bildirimlerini getir
     */
    private function getUserNotifications(): Collection
    {
        try {
            return Notification::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
