<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PeminjamanController extends Controller
{

    public function create($id)
    {
        $alats = alat_musik::findOrFail($id); // Mengambil semua data dari database
        return view('pages.peminjaman', compact('alats'));
    }   
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat_musik,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
            'alasan' => 'required|string',
        ]);
    
        $alats = alat_musik::findOrFail($request->alat_id);
    
        if ($request->jumlah > $alats->jumlah) {
            return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia.'])->withInput();
        }
    
        // Simpan data peminjaman
        Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => $request->alat_id,
            'jumlah' => $request->jumlah,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'jaminan' => 'KTP',
            'status' => 'menunggu', // Status default
        ]);
    
        // Kurangi jumlah alat yang tersedia
        // $alat->decrement('jumlah', $request->jumlah);
    
        return redirect()->route('pages.peminjaman')->with('success', 'Peminjaman berhasil diajukan!');
    }
    
}
