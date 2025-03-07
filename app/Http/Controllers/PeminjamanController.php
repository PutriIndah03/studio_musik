<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use App\Models\studio_musik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = peminjaman::with('studio_musik', 'alat_musik')->get();
        return view('pages.peminjaman.index', compact('peminjaman'));
    }
    public function create()
    {
        $alats = alat_musik::all(); // Mengambil semua data dari database
        return view('pages.peminjaman.create', compact('alats'));
    } 
    public function createStudio($id)
    {
        $alats = alat_musik::all();
        $studios = studio_musik::findOrFail($id);
        return view('pages.peminjaman.createStudio', compact('studios', 'alats'));
    }
      
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'alat_id' => 'required|exists:alat_musik,id',
    //         'jumlah' => 'required|integer|min:1',
    //         'tanggal_pinjam' => 'required|date',
    //         'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
    //         'alasan' => 'required|string',
    //     ]);
    
    //     $alats = alat_musik::findOrFail($request->alat_id);
    
    //     if ($request->jumlah > $alats->jumlah) {
    //         return back()->withErrors(['jumlah' => 'Jumlah melebihi stok yang tersedia.'])->withInput();
    //     }
    
    //     // Simpan data peminjaman
    //     Peminjaman::create([
    //         'user_id' => Auth::id(),
    //         'alat_id' => $request->alat_id,
    //         'jumlah' => $request->jumlah,
    //         'tanggal_pinjam' => $request->tanggal_pinjam,
    //         'tanggal_kembali' => $request->tanggal_kembali,
    //         'alasan' => $request->alasan,
    //         'jaminan' => 'KTP',
    //         'status' => 'menunggu', // Status default
    //     ]);
    
    //     // Kurangi jumlah alat yang tersedia
    //     // $alat->decrement('jumlah', $request->jumlah);
    
    //     return redirect()->route('dashboard.mahasiswa')->with('success', 'Peminjaman berhasil diajukan!');
    // }
    
    public function store(Request $request)
{
    $request->validate([
        'alat_id' => 'required|array',
        'alat_id.*' => 'exists:alat_musik,id',
        'tanggal_pinjam' => 'required|date',
        'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
        'alasan' => 'required|string|max:255',
    ]);

    foreach ($request->alat_id as $alatId) {
        Peminjaman::create([
            'user_id' => Auth::id(), // Menggunakan ID pengguna yang sedang login
            'alat_id' => $alatId,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'jaminan' => 'KTP', // Jaminan default
            'status' => 'menunggu', // Status awal peminjaman
        ]);
    }

    return redirect()->route('dashboard.mahasiswa')->with('success', 'Peminjaman berhasil diajukan.');
}
    
}
