<?php

use App\Http\Controllers\AkunStafController;
use App\Http\Controllers\AlatMusikController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudioMusikController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [StudioMusikController::class, 'index2'])->name('dashboard.mahasiswa');
Route::resource('studio_musik', StudioMusikController::class);
Route::resource('alat_musik', AlatMusikController::class);
Route::resource('akun_staf', AkunStafController::class);
Route::post('/akun_staf/{id}/reset_password', [AkunStafController::class, 'resetPassword'])->name('akun_staf.reset_password');


Route::get('/check-accounts', function (Request $request) {
    $username = $request->query('username');
    
    if (!$username) {
        return response()->json([]);
    }

    $roles = User::where('username', $username)->pluck('role')->toArray();

    return response()->json($roles);
})->name('check.accounts');
