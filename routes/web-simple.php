<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Simple authentication routes (must be before catch-all)
Route::get('auth-login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login.show');
Route::get('auth-login-simple', [App\Http\Controllers\AuthController::class, 'showSimpleLogin'])->name('login.simple');
Route::post('auth-login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Test route
Route::get('/test', function () {
    return view('welcome');
});

// Sidebar test route
Route::get('/test-sidebar', function () {
    return view('test-sidebar');
});
