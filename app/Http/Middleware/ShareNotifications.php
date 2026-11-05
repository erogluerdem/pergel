<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\NotificationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareNotifications
{
    public function __construct(
        private NotificationService $notificationService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            if ($user instanceof User) {
                // Get unread notifications
                $notifications = $this->notificationService->getUnreadNotifications($user);
                $notificationStats = $this->notificationService->getStats($user);

                // Share with all views
                View::share('userNotifications', $notifications);
                View::share('notificationStats', $notificationStats);
            }
        }

        return $next($request);
    }
}
