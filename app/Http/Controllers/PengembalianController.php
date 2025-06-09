<?php

namespace App\Http\Controllers;

use App\Models\peminjaman;
use App\Models\pengembalian;
use App\Models\studio_musik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\alat_musik;

class PengembalianController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        $userId = Auth::id();
    
        $pengembalian = Peminjaman::with('studio_musik', 'pengembalian')
            ->where('user_id', $userId)
            ->whereHas('pengembalian', function ($query) {
                $query->where('status', '!=', 'Diterima')
                      ->orWhereRaw("TIMESTAMPDIFF(HOUR, updated_at, NOW()) <= 24");
                    // ->orWhereRaw("TIMESTAMPDIFF(MINUTE, updated_at, NOW()) <= 1");
            }) // Menampilkan hanya peminjaman yang masih dalam proses atau yang diterima dalam 24 jam terakhir
            ->paginate(10);
    
        foreach ($pengembalian as $item) {
            $alat_ids = json_decode($item->alat_id, true) ?? [];
            $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
        }
    
        return view('pages.pengembalian.index', compact('pengembalian'));
    }
    

    public function formPengembalian($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
    
        return view('pages.pengembalian.create', [
            'peminjaman' => $peminjaman,
            'studio_musik' => $peminjaman->studio_musik,
            'alat_musik' => $peminjaman->alat_musiks() // Ambil alat musik langsung dari fungsi relasi
        ]);
    }
    

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'peminjaman_id' => 'required|exists:peminjaman,id',
    //         'kondisi' => 'required|string',
    //         'alasan' => 'nullable|string',
    //     ]);

    //     // Mendapatkan data peminjaman terkait
    //     $peminjaman = Peminjaman::find($request->peminjaman_id);
        
    //     if (!$peminjaman) {
    //         return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
    //     }

    //     // Menandai waktu pengembalian
    //     $tanggalPengembalian = Carbon::now();

    //     // Menentukan status keterlambatan
    //     $tanggalJatuhTempo = Carbon::parse($peminjaman->tanggal_kembali);
    //     $keterangan_pengembalian = $tanggalPengembalian->greaterThan($tanggalJatuhTempo) ? 'Terlambat' : 'Tepat Waktu';
        
    //     // Menyimpan data pengembalian ke tabel pengembalian
    //    Pengembalian::create([
    //         'peminjaman_id' => $peminjaman->id,
    //         'tanggal_pengembalian' => $tanggalPengembalian,
    //         'keterangan_pengembalian' => $keterangan_pengembalian,
    //         'kondisi' => $request->kondisi,
    //         'alasan' => $request->alasan,
    //         'status' => 'menunggu'
    //     ]);
    //     // Menandai peminjaman sebagai selesai
    //     // $peminjaman->status = 'Selesai';
    //     $peminjaman->save();

    //     return redirect()->route('peminjaman.index')->with('success', 'Pengembalian berhasil ajukan.');
    // }


public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'peminjaman_id' => 'required|exists:peminjaman,id',
        'alat_id' => 'required|array',
        'alat_id.*' => 'exists:alat_musik,id',
        // 'kondisi' => 'required|array',
        // 'kondisi.*' => 'in:Baik,Rusak Ringan,Rusak',
        'alasan' => 'nullable|string',
    ]);

    // Ambil data peminjaman
    $peminjaman = Peminjaman::find($request->peminjaman_id);
    if (!$peminjaman) {
        return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
    }

    // Menentukan waktu pengembalian
    $tanggalPengembalian = Carbon::now();
    $tanggalJatuhTempo = Carbon::parse($peminjaman->tanggal_kembali);
    $keterangan_pengembalian = $tanggalPengembalian->greaterThan($tanggalJatuhTempo) ? 'Terlambat' : 'Tepat Waktu';

    // Menyimpan kondisi alat dalam format JSON
    // $kondisiAlatJson = json_encode($request->kondisi);
        $kondisiAlatJson = [];
    foreach ($request->alat_id as $alat_id) {
        $kondisiAlatJson[$alat_id] = 'Baik';
    }

    // Simpan data pengembalian
    Pengembalian::create([
        'peminjaman_id' => $peminjaman->id,
        'tanggal_pengembalian' => $tanggalPengembalian,
        'keterangan_pengembalian' => $keterangan_pengembalian,
        'alat_id' => json_encode($request->alat_id), // Simpan ID alat sebagai array JSON
        'kondisi' => json_encode($kondisiAlatJson),    // Simpan kondisi dalam JSON
        'alasan' => $request->alasan,
        'status' => 'Menunggu'
    ]);
    return redirect()->route('pengembalian.index')
    ->with('setActiveMenu', '/pengembalian')
    ->with('success', 'Pengembalian berhasil diajukan.');


}

}
