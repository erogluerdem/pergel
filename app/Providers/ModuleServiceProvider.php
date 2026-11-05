<?php

namespace App\Providers;

use App\Services\ModuleManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class, function () {
            return new ModuleManager();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @var ModuleManager $manager */
        $manager = $this->app->make(ModuleManager::class);
        $installed = $manager->getInstalledModules();

        foreach ($installed as $slug => $manifest) {
            if (empty($manifest['enabled'])) {
                continue;
            }

            $moduleDir = $manifest['path'];

            // 1. Rotaları yükle
            $routesFile = $manifest['routes'] ?? 'routes.php';
            $fullRoutesPath = $moduleDir . DIRECTORY_SEPARATOR . $routesFile;
            if (File::exists($fullRoutesPath)) {
                Route::middleware('web')
                    ->group($fullRoutesPath);
            }

            // 2. View dizinini yükle
            $viewsDir = $manifest['views'] ?? 'views';
            $fullViewsPath = $moduleDir . DIRECTORY_SEPARATOR . $viewsDir;
            if (File::isDirectory($fullViewsPath)) {
                $this->loadViewsFrom($fullViewsPath, $slug);
                $this->loadViewsFrom($fullViewsPath, 'pergel-module');
            }

            // 3. Veritabanı Migrations yükle
            $migrationsDir = $manifest['migrations'] ?? 'database/migrations';
            $fullMigrationsPath = $moduleDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $migrationsDir);
            if (File::isDirectory($fullMigrationsPath)) {
                $this->loadMigrationsFrom($fullMigrationsPath);
            }
        }

        // Blade Direktifleri
        Blade::if('moduleLoaded', function (string $slug) use ($manager) {
            return $manager->isInstalled($slug) && $manager->isEnabled($slug);
        });

        Blade::if('moduleProvides', function (string $feature) use ($manager) {
            return $manager->providesFeature($feature);
        });

        // Global view composer
        view()->composer('*', function ($view) use ($manager) {
            $view->with('moduleManager', $manager);
        });
    }
}
