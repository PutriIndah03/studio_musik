<?php

use App\Http\Controllers\AkunStafController;
use App\Http\Controllers\AlatMusikController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JadwalPeminjamanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatPeminjamanController;
use App\Http\Controllers\ValidasiPeminjamanController;
use App\Http\Controllers\ValidasiPengembalianController;
use App\Http\Controllers\StudioMusikController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Http\Request;


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('password/reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');

Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard/mahasiswa', [StudioMusikController::class, 'dMhs'])->name('dashboard.mahasiswa');
Route::get('/search', [StudioMusikController::class, 'search'])->name('search');
Route::get('/dashboard', [StudioMusikController::class, 'dStaf'])->name('dashboard.staf');
Route::resource('studio_musik', StudioMusikController::class);
Route::resource('alat_musik', AlatMusikController::class);
Route::resource('akun_staf', AkunStafController::class);
Route::post('/akun_staf/{id}/reset_password', [AkunStafController::class, 'resetPassword'])->name('akun_staf.reset_password');

Route::middleware(['auth'])->group(function () {
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('/peminjaman/store2', [PeminjamanController::class, 'store2'])->name('peminjaman.store2');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::get('/peminjaman/createStudio/{studio_musik}', [PeminjamanController::class, 'createStudio'])->name('peminjaman.createStudio');
    Route::post('/cek-alat-terpakai', [PeminjamanController::class, 'cekAlatTerpakai']);

    Route::get('validasipeminjaman', [ValidasiPeminjamanController::class,'index']);
    Route::post('/peminjaman/{id}/approve', [ValidasiPeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [ValidasiPeminjamanController::class, 'reject'])->name('peminjaman.reject');

    Route::get('/peminjaman/{id}/pengembalian', [PengembalianController::class, 'formPengembalian'])->name('peminjaman.formPengembalian');
    Route::post('/pengembalian/store', [PengembalianController::class, 'store'])->name('peminjaman.prosesPengembalian');
    Route::get('pengembalian', [pengembalianController::class,'index'])->name('pengembalian.index');

    Route::get('validasipengembalian', [ValidasiPengembalianController::class,'index']);
    Route::post('/pengembalian/{id}/approve', [ValidasiPengembalianController::class, 'approve'])->name('pengembalian.approve');
    Route::post('/pengembalian/{id}/reject', [ValidasiPengembalianController::class, 'reject'])->name('pengembalian.reject');

    Route::get('jadwalPeminjaman', [JadwalPeminjamanController::class,'index']);
    Route::get('riwayatPeminjamanMhs', [RiwayatPeminjamanController::class,'index'])->name('riwayatPeminjamanMhs');
    Route::get('riwayatPeminjaman', [RiwayatPeminjamanController::class,'index2'])->name('riwayatPeminjaman');
    Route::get('laporan', [RiwayatPeminjamanController::class,'laporan'])->name('laporan');
    Route::get('/riwayat-peminjamanmhs/download', [RiwayatPeminjamanController::class, 'download'])->name('riwayatPeminjamanMhs.download');
    Route::get('/riwayat-peminjaman/download', [RiwayatPeminjamanController::class, 'downloadRiwayatAdmin'])->name('riwayatPeminjaman.download');
    Route::get('/laporan/download', [RiwayatPeminjamanController::class, 'downloadLaporan'])->name('laporan.download');

    Route::get('profile', [ProfileController::class,'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/ubah-password', [ProfileController::class, 'ubahPassword'])->name('password.update');



});

Route::get('/check-accounts', function (Request $request) {
    $username = $request->query('username');
    
    if (!$username) {
        return response()->json([]);
    }

    $roles = User::where('username', $username)->pluck('role')->toArray();

    return response()->json($roles);
})->name('check.accounts');

Route::get('/auto-logout', function () {
    Auth::logout();
    return redirect('/login')->with('message', 'Anda telah logout karena tidak aktif selama 30 menit.');
})->name('logout.auto');
