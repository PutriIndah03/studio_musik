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
    
        // Ambil semua pengembalian dengan relasi ke peminjaman, kecuali yang sudah diterima lebih dari 24 jam
        $pengembalian = Pengembalian::with('peminjaman')
            ->where(function ($query) {
                $query->where('status', '!=', 'Diterima')
                      ->orWhereRaw("TIMESTAMPDIFF(HOUR, updated_at, NOW()) <= 24");
                    //   ->orWhereRaw("TIMESTAMPDIFF(MINUTE, updated_at, NOW()) <= 1");
            })
            ->paginate(5);
    
        // Proses alat musik untuk setiap peminjaman
        foreach ($peminjaman as $item) {
            $alat_ids = !empty($item->alat_id) ? json_decode($item->alat_id, true) : [];
    
            $item->alat_musik = is_array($alat_ids) && !empty($alat_ids)
                ? alat_musik::whereIn('id', $alat_ids)->get()
                : collect();
        }
    
        // Proses alat musik untuk setiap pengembalian berdasarkan peminjaman
        foreach ($pengembalian as $item) {
            if ($item->peminjaman) {
                $alat_ids = !empty($item->peminjaman->alat_id) ? json_decode($item->peminjaman->alat_id, true) : [];
    
                $item->alat_musik = is_array($alat_ids) && !empty($alat_ids)
                    ? alat_musik::whereIn('id', $alat_ids)->get()
                    : collect();
            } else {
                $item->alat_musik = collect();
            }
        }
    
        return view('pages.validasi_pengembalian', compact('peminjaman', 'pengembalian'));
    }
    
    
    // public function approve($id)
    // {
    //     $pengembalian = Pengembalian::findOrFail($id);
    
    //     // Ubah status pengembalian menjadi 'diterima'
    //     $pengembalian->update(['status' => 'Diterima']);
    
    //     // Ubah status peminjaman menjadi 'dikembalikan'
    //     if ($pengembalian->peminjaman) {
    //         $pengembalian->peminjaman->update(['status' => 'Dikembalikan']);
    //     }
    
    //     return redirect()->back()->with('success', 'Pengembalian telah disetujui.');
    // }
    

    public function approve($id, Request $request)
    {
        // Menemukan data pengembalian berdasarkan ID
        $pengembalian = Pengembalian::findOrFail($id);
    
        // Validasi input kondisi dan alasan
        $validated = $request->validate([
            'kondisi' => 'required|array', // Memastikan kondisi untuk setiap alat musik
            'kondisi.*' => 'in:Baik,Rusak Ringan,Rusak', // Kondisi yang valid
            'detail' => 'nullable|string|max:1000', 
        ]);
    
        // Menyimpan kondisi untuk setiap alat musik
        $kondisi = json_encode($validated['kondisi']); // Menyimpan kondisi dalam format JSON
    
        // Menyimpan alasan pengembalian
        $pengembalian->update([
            'status' => 'Diterima',
            'kondisi' => $kondisi, // Menyimpan kondisi dalam kolom 'kondisi'
            'detail' => $validated['detail'], // Menyimpan alasan dalam kolom 'detail'
        ]);
    
        // Mengubah status peminjaman menjadi 'Dikembalikan'
        if ($pengembalian->peminjaman) {
            $pengembalian->peminjaman->update(['status' => 'Dikembalikan']);
        }
    
        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Pengembalian telah disetujui dan kondisi telah dicatat.');
    }
    

    public function reject(Request $request, $id)
    {
        $pengembalian = Pengembalian::findOrFail($id);
    
        // Ubah status pengembalian menjadi 'ditolak'
        $pengembalian->update([
            'status' => 'Ditolak',
            'alasan' => $request->input('alasan'),
        ]);
    
        // Kembalikan status peminjaman menjadi 'disetujui'
        // if ($pengembalian->peminjaman) {
        //     $pengembalian->peminjaman->update([
        //         'status' => 'Disetujui',
        //     ]);
        // }
    
        return redirect()->back()->with('success', 'Pengembalian ditolak, status peminjaman dikembalikan menjadi disetujui.');
    }
    

}
