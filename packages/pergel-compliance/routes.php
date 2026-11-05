<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PERGEL Mevzuat & Finans Paketi (pergel-compliance)
|--------------------------------------------------------------------------
| Bu rota dosyası modül kurulduğunda ModuleServiceProvider tarafından
| otomatik olarak yüklenir.
|
*/

Route::middleware(['auth'])->group(function() {
    // ÇŞB Poz Kütüphanesi & SGK & Resmi İşlemler
    Route::get('csb_poz', [App\Http\Controllers\CsbPozController::class, 'index'])->name('csb_poz.index');
    Route::get('sgk', [App\Http\Controllers\SgkLaborController::class, 'index'])->name('sgk.index');
    Route::get('price_differences', [App\Http\Controllers\PriceDifferenceController::class, 'index'])->name('price_differences.index');
    
    // Resmi İnşaat Hakedişleri & Yeşil Defter
    Route::get('progress-payments', [App\Http\Controllers\ProgressPaymentController::class, 'index'])->name('progress-payments.index');
    Route::get('finance/budget-simulators', [App\Http\Controllers\BudgetSimulatorController::class, 'index'])->name('finance.budget-simulators.index');
    
    // İhale & Teklif Yönetimi
    Route::get('tenders', [App\Http\Controllers\TenderManagementController::class, 'index'])->name('tenders.index');
});
