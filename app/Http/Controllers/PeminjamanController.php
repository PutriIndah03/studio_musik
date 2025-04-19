<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\Peminjaman;
use App\Models\studio_musik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
    
        // Ambil ID user yang sedang login
        $userId = Auth::id();
    
        // Ambil hanya data peminjaman milik user yang login dan belum diajukan pengembalian,
        // serta tidak memiliki status "Dikembalikan"
        $peminjaman = Peminjaman::with('studio_musik', 'pengembalian')
            ->where('user_id', $userId)
            ->where('status', '!=', 'Dikembalikan') // Tambahkan pengecualian untuk status "Dikembalikan"
            ->whereDoesntHave('pengembalian', function ($query) {
                $query->where('status', 'Menunggu');
            })
            ->paginate(5);
    
        foreach ($peminjaman as $item) {
            // Ubah JSON 'alat_id' menjadi array
            $alat_ids = json_decode($item->alat_id, true) ?? [];
    
            // Ambil data alat musik berdasarkan ID dalam array
            $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
        }
    
        return view('pages.peminjaman.index', compact('peminjaman'));
    }
    
    
    public function create()
    {
        $alats = alat_musik::all();
        return view('pages.peminjaman.create', compact('alats'));
    }

    public function createStudio()
    {
        $alats = alat_musik::all();
        $studios = studio_musik::all();
        return view('pages.peminjaman.createStudio', compact('studios', 'alats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|array|min:1',
            'alat_id.*' => 'exists:alat_musik,id',
            'tanggal_pinjam' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_kembali' => [
                'required',
                'date_format:Y-m-d\TH:i',
                function ($attribute, $value, $fail) use ($request) {
                    $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
                    $tanggalKembali = Carbon::parse($value);
                    if ($tanggalKembali->gt($tanggalPinjam->copy()->addDays(2))) {
                        $fail("Tanggal kembali maksimal hanya boleh 2 hari setelah tanggal pinjam.");
                    }
                },
            ],
            'alasan' => 'required|string|max:255',
        ], [
            'alat_id.required' => 'Anda harus memilih setidaknya satu alat untuk dipinjam.',
            'alat_id.min' => 'Pilih minimal satu alat.',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'alat_id' => json_encode($request->alat_id), // Simpan dalam format JSON
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'jaminan' => 'KTP',
            'status' => 'Menunggu',
        ]);
        return redirect()->route('peminjaman.index')
        ->with('setActiveMenu', '/peminjaman')
        ->with('success', 'Peminjaman berhasil diajukan.');


        // return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'studio_id' => 'required|exists:studio_musik,id',
            'tanggal_pinjam' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_kembali' => [
                'required',
                'date_format:Y-m-d\TH:i',
                function ($attribute, $value, $fail) use ($request) {
                    $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
                    $tanggalKembali = Carbon::parse($value);
                    if ($tanggalKembali->gt($tanggalPinjam->copy()->addDays())) {
                        $fail("Tanggal kembali maksimal hanya boleh 1 (24 jam) hari setelah tanggal pinjam.");
                    }
                }
            ],
            'alasan' => 'required|string|max:255',
            'alat_id' => 'required|array|min:1',
            'alat_id.*' => 'exists:alat_musik,id',
        ], [
            'alat_id.required' => 'Anda harus memilih setidaknya satu alat untuk dipinjam.',
            'alat_id.min' => 'Pilih minimal satu alat.',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'studio_id' => $request->studio_id,
            'alat_id' => json_encode($request->alat_id),
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali' => $request->tanggal_kembali,
            'alasan' => $request->alasan,
            'jaminan' => 'KTM',
            'status' => 'Menunggu',
        ]);
        return redirect()->route('peminjaman.index')
    ->with('setActiveMenu', '/peminjaman')
    ->with('success', 'Peminjaman berhasil diajukan.');



        // return redirect()->route('peminjaman.index')->with('success', 'Peminjaman berhasil diajukan.');
    }
}
