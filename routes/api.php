<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\PartnerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth');

// Ortaklar ve sermaye işlemleri için API
Route::middleware(['auth'])->group(function () {
    Route::get('/partners', [PartnerController::class, 'index']);
    Route::post('/partners', [PartnerController::class, 'store']);
    Route::post('/partners/{partner}/capital', [PartnerController::class, 'addCapital']);
});

// Notification API routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
        Route::get('/stats', [NotificationController::class, 'stats']);
        Route::get('/count', [NotificationController::class, 'count']);
    });

    // Activities API routes
    Route::prefix('activities')->group(function () {
        Route::get('/recent', [ActivityController::class, 'recent']);
        Route::get('/upcoming', [ActivityController::class, 'upcoming']);
        Route::get('/summary', [ActivityController::class, 'summary']);
    });
});

// Public analytics baseline endpoint for quick testing
Route::get('/analytics/baseline', [App\Http\Controllers\AnalyticsController::class, 'baseline']);

// Villa API
Route::get('/projects/{project}/villas', [App\Http\Controllers\Api\VillaController::class, 'index']);

// QR Code API - Teknik Destek
Route::prefix('qr')->group(function () {
    Route::post('/scan/{token}', [App\Http\Controllers\Api\QrCodeApiController::class, 'scan']);
    Route::get('/stats/{token}', [App\Http\Controllers\Api\QrCodeApiController::class, 'getQrStats']);
    Route::post('/request/{token}', [App\Http\Controllers\Api\QrCodeApiController::class, 'createRequestFromQr']);
});

// FAZ-5: MOBİL UYGULAMA API KATMANI
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    // Auth actions
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Mobile App endpoints
    Route::prefix('mobile')->group(function () {
        Route::get('/projects', [\App\Http\Controllers\Api\MobileAppController::class, 'getProjects']);
        Route::post('/site-daily-logs', [\App\Http\Controllers\Api\MobileAppController::class, 'storeDailyLog']);
        Route::post('/timesheet-upload', [\App\Http\Controllers\Api\MobileAppController::class, 'uploadTimesheet']);
        Route::get('/executive-summary', [\App\Http\Controllers\Api\MobileAppController::class, 'getExecutiveSummary']);
    });
});
