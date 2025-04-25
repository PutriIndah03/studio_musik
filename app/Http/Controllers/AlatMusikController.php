<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use Illuminate\Http\Request;

class AlatMusikController extends Controller
{
    public function index()
    {
        $alats = alat_musik::paginate(5); // Mengambil semua data dari database
        return view('pages.alat_musik.index', compact('alats'));
    }
    public function create()
    {
        return view('pages.alat_musik.create');
    }

    // Menyimpan data studio musik ke database
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:255|unique:alat_musik,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Tradisional,Modern',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            // 'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak', // Validasi kondisi
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ], [
            'kode.unique' => 'Kode alat musik sudah terdaftar.',
        ]);
    
    
        $gambarPath = null;
        if ($request->hasFile('foto')) {
            $gambarPath = $request->file('foto')->store('alat-musik', 'public'); // Simpan gambar di storage
        }
    
        alat_musik::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'foto' => $gambarPath,
            // 'jumlah' => $request->jumlah,
            'kondisi' => $request->kondisi,
            'status' => $request->status,
        ]);
    
        return redirect()->route('alat_musik.index')->with('success', 'Studio Musik berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $alat = alat_musik::findOrFail($id);
        return view('pages.alat_musik.update', compact('alat'));
    }    

    public function update(Request $request, $id)
    {
    
        $request->validate([
            'kode' => 'required|string|max:255|unique:alat_musik,kode,' . $id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|in:Tradisional,Modern',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            // 'jumlah' => 'required|integer|min:1',
            'kondisi' => 'required|in:Baik,Rusak Ringan,Rusak',
            'status' => 'required|in:Tersedia,Tidak Tersedia',
        ], [
            'kode.unique' => 'Kode alat musik sudah terdaftar.',
        ]);

        $alat = alat_musik::findOrFail($id);
        $alat->kode = $request->kode;
        $alat->nama = $request->nama;
        $alat->kategori = $request->kategori;
        // $alat->jumlah = $request->jumlah;
        $alat->kondisi = $request->kondisi;
        $alat->status = $request->status;
    
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($alat->foto && file_exists(storage_path("app/public/{$alat->foto}"))) {
                unlink(storage_path("app/public/{$alat->foto}"));
            }
    
            // Simpan foto baru
            $gambarPath = $request->file('foto')->store('alat-musik', 'public');
            $alat->foto = $gambarPath;
        }
    
        $alat->save();
       
        return redirect()->route('alat_musik.index')->with('success', 'Data alat musik berhasil diperbarui.');
    }
    
    

    public function destroy($id)
{
    $studio = alat_musik::findOrFail($id);

    // Hapus foto jika ada
    if ($studio->foto && file_exists(storage_path("app/public/{$studio->foto}"))) {
        unlink(storage_path("app/public/{$studio->foto}"));
    }

    $studio->delete();

    return redirect()->route('alat_musik.index')->with('success', 'Studio Musik berhasil dihapus.');
}
}
