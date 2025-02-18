<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudioMusikController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('pages.dashboard_mahasiswa');
});
Route::get('/studio_musik', function () {
    return view('pages.studio_musik.index');
});
Route::get('/studio-musik/create', [StudioMusikController::class, 'create'])->name('studio_musik.create');
Route::get('/studio-musik', [StudioMusikController::class, 'index'])->name('studio_musik.index');
Route::post('/studio-musik', [StudioMusikController::class, 'store'])->name('studio_musik.store');
