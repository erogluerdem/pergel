<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Display a listing of notifications.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        // Get filtered notifications
        $query = Notification::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc');

        // Filter by read status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $notifications = $query->paginate(20);

        // Get statistics
        $stats = $this->notificationService->getStats($user);

        // Get notification types and priorities for filters
        $types = Notification::select('type')
                            ->where('user_id', $user->id)
                            ->distinct()
                            ->pluck('type');

        $priorities = ['low', 'medium', 'high', 'urgent'];

        return view('notifications.index', compact('notifications', 'stats', 'types', 'priorities'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Request $request, int $id): RedirectResponse
    {
        $success = $this->notificationService->markAsRead($id, auth()->user());

        if ($success) {
            return back()->with('success', 'Bildirim okundu olarak işaretlendi.');
        }

        return back()->with('error', 'Bildirim bulunamadı.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): RedirectResponse
    {
        $count = $this->notificationService->markAllAsRead(auth()->user());

        return back()->with('success', "{$count} bildirim okundu olarak işaretlendi.");
    }
}
