<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Create a notification for a specific user
     */
    public function notifyUser(User $user, array $data): Notification
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'data' => $data['data'] ?? [],
            'priority' => $data['priority'] ?? 'medium',
            'is_actionable' => $data['is_actionable'] ?? false,
            'action_url' => $data['action_url'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Broadcast notification to multiple users
     */
    public function broadcast(array $userIds, array $data): Collection
    {
        $notifications = collect();
        
        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $notifications->push($this->notifyUser($user, $data));
            }
        }
        
        return $notifications;
    }

    /**
     * Notify users with a specific role
     */
    public function notifyRole(string $roleName, array $data): Collection
    {
        $users = User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();

        return $this->broadcast($users->pluck('id')->toArray(), $data);
    }

    /**
     * Create task assignment notification
     */
    public function taskAssigned(User $assignee, $task): Notification
    {
        return $this->notifyUser($assignee, [
            'type' => 'task_assigned',
            'title' => 'Yeni Görev Atandı',
            'message' => "Size '{$task->title}' görevi atandı.",
            'data' => ['task_id' => $task->id],
            'priority' => $task->priority,
            'is_actionable' => true,
            'action_url' => route('tasks.show', $task->id),
        ]);
    }

    /**
     * Create inventory low stock notification
     */
    public function lowStockAlert(User $user, $inventoryItem): Notification
    {
        return $this->notifyUser($user, [
            'type' => 'low_stock',
            'title' => 'Düşük Stok Uyarısı',
            'message' => "'{$inventoryItem->name}' ürününde stok azalıyor. Mevcut: {$inventoryItem->quantity} {$inventoryItem->unit}",
            'data' => ['inventory_item_id' => $inventoryItem->id],
            'priority' => 'high',
            'is_actionable' => true,
            'action_url' => route('inventory.show', $inventoryItem->id),
        ]);
    }

    /**
     * Get unread notifications for a user
     */
    public function getUnreadNotifications(User $user): Collection
    {
        return $user->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, User $user): bool
    {
        $notification = $user->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(User $user): int
    {
        return $user->notifications()
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Clean up expired notifications
     */
    public function cleanupExpired(): int
    {
        return Notification::where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Get notification statistics
     */
    public function getStats(User $user): array
    {
        $notifications = $user->notifications();
        
        return [
            'total' => $notifications->count(),
            'unread' => $notifications->unread()->count(),
            'urgent' => $notifications->where('priority', 'urgent')->count(),
            'actionable' => $notifications->where('is_actionable', true)->unread()->count(),
        ];
    }
}
