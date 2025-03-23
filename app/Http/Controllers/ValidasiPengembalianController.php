<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use App\Models\pengembalian;
use Illuminate\Http\Request;

class ValidasiPengembalianController extends Controller
{
    public function index()
    {
        // Ambil semua peminjaman dengan relasi user dan studio musik
        $peminjaman = Peminjaman::with(['user.mahasiswa', 'studio_musik', 'alat_musik'])->get();
    
        // Ambil semua pengembalian dengan relasi ke peminjaman
        $pengembalian = Pengembalian::with('peminjaman')->get();
    
        // Proses alat musik untuk setiap peminjaman
        foreach ($peminjaman as $item) {
            // Pastikan alat_id adalah JSON yang valid sebelum decode
            $alat_ids = !empty($item->alat_id) ? json_decode($item->alat_id, true) : [];
    
            // Pastikan hasil decode adalah array sebelum dipakai dalam whereIn()
            $item->alat_musik = is_array($alat_ids) && !empty($alat_ids)
                ? alat_musik::whereIn('id', $alat_ids)->get()
                : collect();
        }
    
        // Proses alat musik untuk setiap pengembalian berdasarkan peminjaman
        foreach ($pengembalian as $item) {
            if ($item->peminjaman) { // Pastikan peminjaman tidak null
                $alat_ids = !empty($item->peminjaman->alat_id) ? json_decode($item->peminjaman->alat_id, true) : [];
    
                $item->alat_musik = is_array($alat_ids) && !empty($alat_ids)
                    ? alat_musik::whereIn('id', $alat_ids)->get()
                    : collect();
            } else {
                $item->alat_musik = collect(); // Jika tidak ada peminjaman, buat koleksi kosong
            }
        }
    
        return view('pages.validasi_pengembalian', compact('peminjaman', 'pengembalian'));
    }
    
    public function approve($id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
    
        // Ubah status pengembalian menjadi 'diterima'
        $pengembalian->update(['status' => 'diterima']);
    
        // Ubah status peminjaman menjadi 'dikembalikan'
        if ($pengembalian->peminjaman) {
            $pengembalian->peminjaman->update(['status' => 'dikembalikan']);
        }
    
        return redirect()->back()->with('success', 'Pengembalian telah disetujui dan status peminjaman diperbarui.');
    }
    

    public function reject(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
        $pengembalian->update([
            'status' => 'ditolak',
            'alasan' => $request->input('alasan'),
        ]);

        return redirect()->back()->with('success', 'Peminjaman telah ditolak.');
    }

}
