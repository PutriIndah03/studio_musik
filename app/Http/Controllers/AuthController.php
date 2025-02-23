<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            'nim' => 'required|string|unique:mahasiswa,nim|unique:users,username',
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
            'username' => $request->nim,
            'password' => Hash::make($request->password),
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
    
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $user = Auth::user();
    
            // Redirect berdasarkan role
            switch ($user->role) {
                case 'mahasiswa':
                    return redirect()->route('dashboard.mahasiswa');
                case 'staf':
                    return redirect()->route('dashboard.staf');
                case 'pembina':
                    return redirect()->route('dashboard.pembina');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['error' => 'Role tidak valid!']);
            }
        }
    
        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }
    

    // 🔹 LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
