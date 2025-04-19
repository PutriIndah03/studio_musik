<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 🔹 Tampilkan Halaman Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 🔹 PROSES REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim' => [
                'required',
                'string',
                Rule::unique('users', 'username')->where(fn ($query) => $query->where('role', 'mahasiswa')),
            ],
            'prodi' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|unique:mahasiswa,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nim.unique' => 'Nim sudah terdaftar.',
            'email.unique' => 'email sudah terdaftar.',
            'password.confirmed' => 'password dan confirm password tidak sesuai.',

        ]);

        // Simpan data mahasiswa
        $mahasiswa = Mahasiswa::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
            'prodi' => $request->prodi,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
        ]);

        // Simpan user dengan NIM sebagai username
        User::create([
            'nama' => $request->nama,
            'username' => $request->nim,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'role' => 'mahasiswa',
            'mahasiswa_id' => $mahasiswa->id,

        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // 🔹 Tampilkan Halaman Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 🔹 PROSES LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Ambil semua akun dengan username yang sama
        $users = User::where('username', $request->username)->get();
    
        if ($users->isEmpty()) {
            return back()->withErrors(['username' => 'Akun tidak ditemukan.'])->withInput();
        }
    
        // Jika ada lebih dari satu akun dengan username yang sama, wajib memilih role
        if ($users->count() > 1) {
            $request->validate([
                'role' => 'required|string|in:staf,mahasiswa,pembina',
            ]);
    
            // Coba autentikasi dengan username, password, dan role
            if (Auth::attempt([
                'username' => $request->username,
                'password' => $request->password,
                'role' => $request->role
            ], $request->filled('remember'))) {
                $user = Auth::user(); // sudah login, tinggal ambil user-nya
    
                return match ($user->role) {
                    'mahasiswa' => redirect()->route('dashboard.mahasiswa'),
                    'staf', 'pembina' => redirect()->route('dashboard.staf'),
                    default => $this->logoutInvalidRole(),
                };
            }
    
            return back()->withErrors(['password' => 'Password atau role salah.'])->withInput();
        } else {
            // Jika hanya satu akun, langsung autentikasi
            $user = $users->first();
    
            if (Auth::attempt([
                'username' => $user->username,
                'password' => $request->password,
            ], $request->filled('remember'))) {
                Auth::login($user);
    
                return match ($user->role) {
                    'mahasiswa' => redirect()->route('dashboard.mahasiswa'),
                    'staf', 'pembina' => redirect()->route('dashboard.staf'),
                    default => $this->logoutInvalidRole(),
                };
            }
    
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }
    }
    

    // Fungsi untuk menangani role tidak valid
    private function logoutInvalidRole()
    {
        Auth::logout();
        return redirect()->route('login')->withErrors(['error' => 'Role tidak valid!']);
    }
    
    

    // 🔹 LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
