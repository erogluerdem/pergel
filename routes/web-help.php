<?php
// Basit bir yardım sayfası rotası
Route::get('/help', function() {
    return view('help.index');
})->name('help.index');
