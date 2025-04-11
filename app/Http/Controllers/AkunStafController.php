<?php

namespace App\Http\Controllers;

use App\Models\Staf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AkunStafController extends Controller
{
    public function index()
    {
        $akuns = Staf::paginate(5); // Mengambil semua data dari database
        return view('pages.akun_staf.index', compact('akuns'));
    }
    public function create()
    {
        return view('pages.akun_staf.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nim' => [
                'required',
                'string',
                Rule::unique('users', 'username')->where(fn ($query) => $query->where('role', 'staf')),
            ],
            'nama' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('staf', 'email'), // Cek unik hanya di tabel staf
            ],
        ], [
            'nim.unique' => 'NIM sudah digunakan oleh staf lain.',
            'email.unique' => 'Email sudah digunakan oleh staf lain.',
        ]);
    
        // Simpan data staf
        $staf = Staf::create([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'email' => $request->email,
        ]);
    
        // Simpan user dengan role staf
        User::create([
            'nama' => $request->nama,
            'username' => $request->nim,
            'password' => Hash::make($request->nim),
            'role' => 'staf', // Role hanya disimpan di tabel users
            'staf_id' => $staf->id,
        ]);
    
        return redirect()->route('akun_staf.index')->with('success', 'Akun staf berhasil dibuat.');
    }
    // public function edit($id)
    // {
    //     $staf = Staf::findOrFail($id);
    //     return view('pages.akun_staf.update', compact('staf'));
    // }  
    public function resetPassword($id)
    {
        $akun = User::findOrFail($id);
    
        // Gunakan NIM sebagai password default
        $defaultPassword = bcrypt($akun->username); 
    
        $akun->update(['password' => $defaultPassword]);
    
        return redirect()->route('akun_staf.index')->with('success', 'Password berhasil direset menjadi NIM!');
    }
    
    public function destroy($id)
    {
        $staf = Staf::findOrFail($id);
    
        // Hapus akun pengguna terkait di tabel users
        User::where('username', $staf->nim)->where('role', 'staf')->delete();
    
        // Hapus akun staf dari tabel staf
        $staf->delete();
    
        return redirect()->route('akun_staf.index')->with('success', 'Akun staf berhasil dihapus.');
    }

}
