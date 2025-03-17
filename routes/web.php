<?php

use App\Http\Controllers\AkunStafController;
use App\Http\Controllers\AlatMusikController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ValidasiPeminjamanController;
use App\Http\Controllers\StudioMusikController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard/mahasiswa', [StudioMusikController::class, 'index2'])->name('dashboard.mahasiswa');
Route::resource('studio_musik', StudioMusikController::class);
Route::resource('alat_musik', AlatMusikController::class);
Route::resource('akun_staf', AkunStafController::class);
Route::post('/akun_staf/{id}/reset_password', [AkunStafController::class, 'resetPassword'])->name('akun_staf.reset_password');

Route::get('/dashboard', function () {
    return  view ('pages.dashboard_staf');
})->name('dashboard.staf');

Route::middleware(['auth'])->group(function () {
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/store2', [PeminjamanController::class, 'store2'])->name('peminjaman.store2');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::get('/peminjaman/createStudio/{studio_musik}', [PeminjamanController::class, 'createStudio'])->name('peminjaman.createStudio');

    Route::get('validasipeminjaman', [ValidasiPeminjamanController::class,'index']);
    Route::post('/peminjaman/{id}/approve', [ValidasiPeminjamanController::class, 'approve'])->name('peminjaman.approve');
Route::post('/peminjaman/{id}/reject', [ValidasiPeminjamanController::class, 'reject'])->name('peminjaman.reject');

});

Route::get('/check-accounts', function (Request $request) {
    $username = $request->query('username');
    
    if (!$username) {
        return response()->json([]);
    }

    $roles = User::where('username', $username)->pluck('role')->toArray();

    return response()->json($roles);
})->name('check.accounts');
