<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Get user's unread notifications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $notifications = $this->notificationService->getUnreadNotifications($user);
            $stats = $this->notificationService->getStats($user);

            return response()->json([
                'notifications' => $notifications,
                'stats' => $stats,
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Bildirimler yüklenirken hata oluştu',
                'error' => $e->getMessage()
            ], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, int $id): JsonResponse
    {
        $success = $this->notificationService->markAsRead($id, auth()->user());

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Bildirim okundu olarak işaretlendi.' : 'Bildirim bulunamadı.'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $count = $this->notificationService->markAllAsRead(auth()->user());

        return response()->json([
            'success' => true,
            'message' => "{$count} bildirim okundu olarak işaretlendi.",
            'marked_count' => $count
        ]);
    }

    /**
     * Get notification statistics
     */
    public function stats(): JsonResponse
    {
        $stats = $this->notificationService->getStats(auth()->user());

        return response()->json($stats);
    }

    /**
     * Get notification count
     */
    public function count(): JsonResponse
    {
        try {
            $user = auth()->user();
            $stats = $this->notificationService->getStats($user);
            $count = $stats['unread'] ?? 0;

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'count' => 0,
                'message' => 'Bildirim sayısı alınamadı'
            ], 500)->header('Content-Type', 'application/json');
        }
    }
}
