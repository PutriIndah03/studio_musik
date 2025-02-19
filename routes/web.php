<?php

use App\Http\Controllers\StudioMusikController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', function () {
    return view('pages.dashboard_mahasiswa');
});
Route::resource('studio_musik', StudioMusikController::class);
Route::get('/studio_musik/create', [StudioMusikController::class, 'create'])->name('studio_musik.create');
Route::get('/studio_musik', [StudioMusikController::class, 'index'])->name('studio_musik.index');
Route::post('/studio_musik', [StudioMusikController::class, 'store'])->name('studio_musik.store');
