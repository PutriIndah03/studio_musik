<?php

use App\Http\Controllers\AlatMusikController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudioMusikController;
use Illuminate\Support\Facades\Route;


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [StudioMusikController::class, 'index2'])->name('dashboard.mahasiswa');
Route::resource('studio_musik', StudioMusikController::class);
Route::resource('alat_musik', AlatMusikController::class);