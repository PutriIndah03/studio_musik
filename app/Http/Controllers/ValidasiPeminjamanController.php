<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;

class ValidasiPeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with(['user.mahasiswa', 'studio_musik', 'alat_musik'])->get();        
        
        foreach ($peminjaman as $item) {
            // Ubah JSON 'alat_id' menjadi array
            $alat_ids = json_decode($item->alat_id, true) ?? [];
    
            // Ambil data alat musik berdasarkan ID dalam array
            $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
        }

        return view('pages.validasi_peminjaman', compact('peminjaman'));
    }
    public function approve($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update(['status' => 'disetujui']);

        return redirect()->back()->with('success', 'Peminjaman telah disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
            'alasan' => $request->input('alasan'),
        ]);

        return redirect()->back()->with('success', 'Peminjaman telah ditolak.');
    }

}
