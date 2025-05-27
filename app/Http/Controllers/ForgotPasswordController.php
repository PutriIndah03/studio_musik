<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }
    
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'nim' => 'required|exists:users,username',
            'role' => 'sometimes|nullable|string|in:mahasiswa,staf,pembina',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.exists' => 'NIM yang dimasukkan tidak ditemukan dalam data pengguna.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);
    
        $query = User::where('username', $request->nim);
    
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
    
        $user = $query->first();
    
        if (!$user) {
            return back()->withErrors(['nim' => 'Akun tidak ditemukan atau role tidak sesuai.'])->withInput();
        }
    
        $status = Password::sendResetLink(['email' => $user->email]);
    
        return $status === Password::RESET_LINK_SENT
        ? back()->with('status', 'Link reset password berhasil dikirim ke email Anda.')
        : back()->withErrors(['email' => 'Gagal mengirim link reset password. Silakan coba lagi nanti.']);
    }
    
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password berhasil direset.')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
