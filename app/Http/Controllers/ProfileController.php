<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login dengan relasi mahasiswa dan staf
        $user = User::with(['mahasiswa', 'staf'])->find(Auth::id());
        
        return view('pages.profile.index', compact('user'));
    }
}
