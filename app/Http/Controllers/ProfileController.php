<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login dengan relasi mahasiswa dan staf
        $user = User::with(['mahasiswa', 'staf'])->find(Auth::id());
        
        return view('pages.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Tentukan apakah user adalah mahasiswa atau staf
        if ($user->mahasiswa) {
            $profile = $user->mahasiswa;
        } elseif ($user->staf) {
            $profile = $user->staf;
        } else {
            return redirect()->back()->with('error', 'Profil tidak ditemukan.');
        }

        // Handle image upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($profile->foto && file_exists(storage_path("app/public/{$profile->foto}"))) {
                unlink(storage_path("app/public/{$profile->foto}"));
            }
    
            // Simpan foto baru
            $gambarPath = $request->file('foto')->store('foto-profil', 'public');
            $profile->foto = $gambarPath;
        }

        // Update profile data
        $profile->nama = $request->nama;
        $profile->email = $request->email;
        $profile->no_hp = $request->no_hp;
        $profile->jenis_kelamin = $request->jenis_kelamin;
        $profile->save();

        // Perbarui nama di tabel user
        User::where('id', $user->id)->update(['nama' => $request->nama]);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
    
    public function ubahPassword(Request $request)
    {
        // Validasi input
        $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal harus 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);
    
        // Ambil user yang sedang login
        $user = Auth::user();
    
        // Periksa apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }
    
        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama.']);
        }
        // Update atau buat data baru jika perlu
        $user = User::updateOrCreate(
            ['id' => $user->id], // kondisi pencarian data
            ['password' => Hash::make($request->new_password)] // data yang diupdate
        );
    
        return back()->with('success', 'Password berhasil diubah.');
    }
    
}
