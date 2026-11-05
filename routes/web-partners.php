<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartnerController;

Route::get('/partners', [PartnerController::class, 'bladeIndex'])->name('partners.index');
Route::post('/partners', [PartnerController::class, 'bladeStore'])->name('partners.store');
Route::put('/partners/{partner}', [PartnerController::class, 'bladeUpdate'])->name('partners.update');
Route::delete('/partners/{partner}', [PartnerController::class, 'bladeDestroy'])->name('partners.destroy');

Route::post('/partners/{partner}/capital', [PartnerController::class, 'bladeAddCapital'])->name('partners.capital');
// Genel ortak işlemleri sayfası
Route::get('/partners/transactions', [PartnerController::class, 'transactionsIndex'])->name('partners.transactions');
Route::get('/partners/transactions/{id}/edit', [PartnerController::class, 'transactionEdit'])->name('partners.transactions.edit');
Route::post('/partners/transactions/{id}', [PartnerController::class, 'transactionUpdate'])->name('partners.transactions.update');
Route::post('/partners/transactions/{id}/delete', [PartnerController::class, 'transactionDelete'])->name('partners.transactions.delete');
