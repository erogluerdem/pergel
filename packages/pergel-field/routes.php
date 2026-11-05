<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PERGEL Saha & Taşeron Paketi (pergel-field)
|--------------------------------------------------------------------------
| Bu rota dosyası modül kurulduğunda ModuleServiceProvider tarafından
| otomatik olarak yüklenir.
|
*/

// Taşeron Dış Paydaş Portalı
Route::prefix('subcontractor')->name('subcontractor.')->group(function () {
    Route::middleware('subcontractor.guest')->group(function () {
        Route::get('login', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'login'])->name('login.attempt');
    });
    Route::post('logout', [App\Http\Controllers\SubcontractorPortal\AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:subcontractor')->group(function () {
        Route::get('dashboard', [App\Http\Controllers\SubcontractorPortal\DashboardController::class, 'index'])->name('dashboard');
        Route::get('services', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'index'])->name('services.index');
        Route::get('services/create', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'create'])->name('services.create');
        Route::post('services', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'store'])->name('services.store');
        Route::get('services/{service}/edit', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'edit'])->name('services.edit');
        Route::put('services/{service}', [App\Http\Controllers\SubcontractorPortal\ServiceEntryController::class, 'update'])->name('services.update');
        Route::get('materials', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'index'])->name('materials.index');
        Route::get('materials/create', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'create'])->name('materials.create');
        Route::post('materials', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'store'])->name('materials.store');
        Route::get('materials/{material}/edit', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'edit'])->name('materials.edit');
        Route::put('materials/{material}', [App\Http\Controllers\SubcontractorPortal\MaterialEntryController::class, 'update'])->name('materials.update');
        Route::get('worker-documents', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'index'])->name('documents.index');
        Route::get('worker-documents/create', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'create'])->name('documents.create');
        Route::post('worker-documents', [App\Http\Controllers\SubcontractorPortal\WorkerDocumentController::class, 'store'])->name('documents.store');
        Route::get('advance-requests', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'index'])->name('advance_requests.index');
        Route::get('advance-requests/create', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'create'])->name('advance_requests.create');
        Route::post('advance-requests', [App\Http\Controllers\SubcontractorPortal\AdvanceRequestController::class, 'store'])->name('advance_requests.store');
        Route::get('bids', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'index'])->name('bids.index');
        Route::get('bids/create', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'create'])->name('bids.create');
        Route::post('bids', [App\Http\Controllers\SubcontractorPortal\TenderBiddingController::class, 'store'])->name('bids.store');
    });
});
