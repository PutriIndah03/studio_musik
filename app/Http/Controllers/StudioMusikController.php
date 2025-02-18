<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\studio_musik;

class StudioMusikController extends Controller
{
    // Menampilkan daftar studio musik
    public function index()
    {
        // $studios = studio_musik::all(); // Mengambil semua data dari database
        return view('pages.studio_musik.index');
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
            'gambar' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('studio-musik', 'public'); // Simpan gambar di storage
        }

        StudioMusik::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('studio-musik.index')->with('success', 'Studio Musik berhasil ditambahkan.');
    }
}
