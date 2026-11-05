<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PERGEL Full Enterprise Paketi (pergel-pro)
|--------------------------------------------------------------------------
| Bu rota dosyası modül kurulduğunda ModuleServiceProvider tarafından
| otomatik olarak yüklenir.
|
*/

Route::middleware(['auth'])->group(function () {
    // BIM 3D Model & Kamera
    Route::get('bim', [App\Http\Controllers\BimModelController::class, 'index'])->name('bim.index');
    Route::get('bim/simulations', [App\Http\Controllers\BimSimulationController::class, 'index'])->name('bim.simulations.index');

    // AI Puantaj & Sözleşme Analizörü
    Route::get('ai-module', [App\Http\Controllers\AiModuleController::class, 'index'])->name('ai-module.index');
    Route::get('ai_timesheet_analyzers', [App\Http\Controllers\AiTimesheetAnalyzerController::class, 'index'])->name('ai_timesheet_analyzers.index');
    Route::get('ai-timesheet-analyzers', [App\Http\Controllers\AiTimesheetAnalyzerController::class, 'index'])->name('ai-timesheet-analyzers.index');

    // Executive BI Dashboard & Analitik
    Route::get('executive_dashboards', [App\Http\Controllers\ExecutiveDashboardController::class, 'index'])->name('executive_dashboards.index');
    Route::get('executive-dashboards', [App\Http\Controllers\ExecutiveDashboardController::class, 'index'])->name('executive-dashboards.index');
    Route::get('analytics', [App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
});
