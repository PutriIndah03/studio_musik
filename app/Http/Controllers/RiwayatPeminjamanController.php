<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatPeminjamanController extends Controller
{
    public function index()
{
    // Cek apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    // Ambil ID user yang sedang login
    $userId = Auth::id();

    // Ambil hanya data peminjaman milik user yang login dengan status "Dikembalikan"
    $peminjaman = peminjaman::with('studio_musik', 'pengembalian')
        ->where('user_id', $userId)
        ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ->get();

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat_peminjaman_mhs', compact('peminjaman'));
}

public function index2()
{
    $peminjaman = peminjaman::with('user.mahasiswa','studio_musik', 'pengembalian')
      
        ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ->get();

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat_peminjaman', compact('peminjaman'));
}


}
