<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use Illuminate\Http\Request;

class ValidasiPeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = peminjaman::with(['user', 'studio_musik', 'alat_musik'])->get();
        return view('pages.validasi_peminjaman', compact('peminjaman'));
    }
}
