<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\PergelLicenseService::class);
        $this->app->singleton(\App\Services\PergelQuotaService::class);
        $this->app->register(\App\Providers\ModuleServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use Bootstrap 5 for all Laravel paginators globally
        Paginator::useBootstrapFive();

        // Register custom Blade directives for licensing
        Blade::if('pergelPro', function () {
            return function_exists('pergel_is_pro') && pergel_is_pro();
        });

        Blade::if('pergelModule', function (string $module) {
            return function_exists('pergel_has_module') && pergel_has_module($module);
        });

        // Register Gates for authorization
        Gate::define('access-module', function ($user, string $module) {
            return app(\App\Services\PergelLicenseService::class)->hasModule($module);
        });

        Gate::define('pergel-pro', function ($user) {
            return app(\App\Services\PergelLicenseService::class)->isPro();
        });

        // Register TopbarComposer for topbar and right-sidebar views
        view()->composer([
            'layouts.topbar',
            'layouts.right-sidebar'
        ], \App\View\Composers\TopbarComposer::class);

        // Share notifications and license/quota with all views
        view()->composer('*', function ($view) {
            try {
                $view->with('pergelLicense', app(\App\Services\PergelLicenseService::class));
                $view->with('pergelQuota', app(\App\Services\PergelQuotaService::class));
            } catch (\Throwable $e) {
                // migration öncesi veya DB yokken sessiz geç
            }

            if (auth()->check()) {
                $userNotifications = \App\Models\Notification::where('user_id', auth()->id())
                    ->where('is_read', false)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                $notificationStats = [
                    'unread' => \App\Models\Notification::where('user_id', auth()->id())
                        ->where('is_read', false)
                        ->count(),
                    'total' => \App\Models\Notification::where('user_id', auth()->id())
                        ->count()
                ];

                $view->with(compact('userNotifications', 'notificationStats'));
            }
        });
    }
}
