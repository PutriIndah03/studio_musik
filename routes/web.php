<?php

use App\Http\Controllers\AlatMusikController;
use App\Http\Controllers\StudioMusikController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [StudioMusikController::class, 'index2']);
Route::resource('studio_musik', StudioMusikController::class);
Route::resource('alat_musik', AlatMusikController::class);