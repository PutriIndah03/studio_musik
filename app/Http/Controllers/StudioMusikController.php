<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use App\Models\studio_musik;

class StudioMusikController extends Controller
{
    // Menampilkan daftar studio musik
    public function index()
    {
        $studios = studio_musik::all(); // Mengambil semua data dari database
        return view('pages.studio_musik.index', compact('studios'));
    }

    // Dashboard mahasiswa
    public function dMhs()
    {
        $studios = studio_musik::all();
        $alats = alat_musik::paginate(4); // Mengambil semua data dari database
        return view('pages.dashboard_mahasiswa', compact('studios','alats'));
    }

    // dashboard staf dan pembina
    public function dStaf()
{
    $totalAlat = alat_musik::count();
    $alatDipinjam = peminjaman::where('status', 'Disetujui')->count();

    $alatRusak = alat_musik::where('kondisi', 'Rusak')->count();

    return view('pages.dashboard_staf', compact('totalAlat', 'alatDipinjam', 'alatRusak'));
}
    public function create()
    {
        return view('pages.studio_musik.create');
    }

    // Menyimpan data studio musik ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'status' => 'required|in:Tersedia,Tidak Tersedia', // Validasi status
        ]);
    
        $gambarPath = null;
        if ($request->hasFile('foto')) {
            $gambarPath = $request->file('foto')->store('studio-musik', 'public'); // Simpan gambar di storage
        }
    
        studio_musik::create([
            'nama' => $request->nama,
            'foto' => $gambarPath,
            'status' => $request->status, // Menyimpan status
        ]);
    
        return redirect()->route('studio_musik.index')->with('success', 'Studio Musik berhasil ditambahkan.');
    }
    public function edit($id)
    {
        $studio = studio_musik::findOrFail($id);
        return view('pages.studio_musik.update', compact('studio'));
    }    

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'status' => 'required|in:Tersedia,Tidak Tersedia', // Validasi status
        ]);
    
        $studio = studio_musik::findOrFail($id);
        $studio->nama = $request->nama;
        $studio->status = $request->status;
    
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($studio->foto && file_exists(storage_path("app/public/{$studio->foto}"))) {
                unlink(storage_path("app/public/{$studio->foto}"));
            }
    
            // Simpan foto baru
            $gambarPath = $request->file('foto')->store('studio-musik', 'public');
            $studio->foto = $gambarPath;
        }
    
        $studio->save();
    
        return redirect()->route('studio_musik.index')->with('success', 'Studio Musik berhasil diperbarui.');
    }
    

    public function destroy($id)
{
    $studio = studio_musik::findOrFail($id);

    // Hapus foto jika ada
    if ($studio->foto && file_exists(storage_path("app/public/{$studio->foto}"))) {
        unlink(storage_path("app/public/{$studio->foto}"));
    }

    $studio->delete();

    return redirect()->route('studio_musik.index')->with('success', 'Studio Musik berhasil dihapus.');
}

// search untuk dashboard mahasiswa
public function search(Request $request)
{
    $query = $request->input('query');

    // Cari berdasarkan nama studio dan alat musik
    $studios = studio_musik::where('nama', 'like', "%{$query}%")->paginate(8);
    $alats = alat_musik::where(function ($q) use ($query) {
        $q->where('nama', 'like', "%{$query}%")
          ->orWhere('kategori', 'like', "%{$query}%");
    })->paginate(8);

    return view('pages.search', compact('query', 'studios', 'alats'));
}

}
