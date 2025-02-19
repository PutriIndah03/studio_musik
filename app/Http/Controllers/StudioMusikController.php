<?php

namespace App\Http\Controllers;

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

    // Menampilkan form tambah studio musik
    public function create()
    {
        return view('pages.studio_musik.create');
    }

    // Menyimpan data studio musik ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required',
            'foto' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
            'status' => 'required|in:Tersedia,Tidak Tersedia', // Validasi status
        ]);
    
        $gambarPath = null;
        if ($request->hasFile('foto')) {
            $gambarPath = $request->file('foto')->store('studio-musik', 'public'); // Simpan gambar di storage
        }
    
        studio_musik::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'foto' => $gambarPath,
            'status' => $request->status, // Menyimpan status
        ]);
    
        return redirect()->route('studio_musik.index')->with('success', 'Studio Musik berhasil ditambahkan.');
    }
    
}
