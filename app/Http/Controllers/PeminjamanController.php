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

    public function createStudio(Request $request)
    {
        // Ambil semua studio
        $studios = studio_musik::all();
    
        // Ambil tanggal yang sedang diminta
        $tanggalPinjam = $request->input('tanggal_pinjam');
        $tanggalKembali = $request->input('tanggal_kembali');
    
        // Jika tanggal pinjam dan kembali disertakan dalam request
        if ($tanggalPinjam && $tanggalKembali) {
            // Ambil alat yang tidak dipinjam pada tanggal yang sama
            $alatDipinjamTanggalYangSama = Peminjaman::whereDate('tanggal_pinjam', '=', $tanggalPinjam)
                ->orWhereDate('tanggal_kembali', '=', $tanggalKembali)
                ->pluck('alat_id')
                ->flatten()
                ->unique();
    
            // Ambil semua alat musik yang tidak dipinjam pada tanggal yang sama
            $alats = alat_musik::whereNotIn('id', $alatDipinjamTanggalYangSama)->get();
        } else {
            // Jika tidak ada tanggal yang diberikan, ambil semua alat
            $alats = alat_musik::all();
            $alatDipinjamTanggalYangSama = collect(); // Jika tidak ada filter tanggal, set ke koleksi kosong
        }
    
        // Kirim data ke view
        return view('pages.peminjaman.createStudio', compact('studios', 'alats', 'alatDipinjamTanggalYangSama'));
    }
       

    // store alat musik
    public function store(Request $request){
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

    // Cek apakah ada alat yang sudah dipinjam pada tanggal tersebut
    $alatBentrok = [];
    foreach ($request->alat_id as $alatId) {
        $adaPeminjaman = Peminjaman::where('status', 'Disetujui')
            ->whereJsonContains('alat_id', (string) $alatId)
            ->where(function ($query) use ($request) {
                $query->whereBetween('tanggal_pinjam', [$request->tanggal_pinjam, $request->tanggal_kembali])
                      ->orWhereBetween('tanggal_kembali', [$request->tanggal_pinjam, $request->tanggal_kembali])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('tanggal_pinjam', '<', $request->tanggal_pinjam)
                            ->where('tanggal_kembali', '>', $request->tanggal_kembali);
                      });
            })
            ->exists();

        if ($adaPeminjaman) {
            $namaAlat = alat_musik::find($alatId)->nama ?? 'Alat Tidak Dikenal';
            $alatBentrok[] = $namaAlat;
        }
    }

    if (!empty($alatBentrok)) {
        return back()->withInput()->withErrors([
            'alat_id' => 'Alat musik ' . implode(', ', $alatBentrok) . ' sudah dipinjam pada tanggal tersebut',
        ]);
    }

    // Simpan data peminjaman
    Peminjaman::create([
        'user_id' => Auth::id(),
        'alat_id' => json_encode($request->alat_id),
        'tanggal_pinjam' => $request->tanggal_pinjam,
        'tanggal_kembali' => $request->tanggal_kembali,
        'alasan' => $request->alasan,
        'jaminan' => 'KTP',
        'status' => 'Menunggu',
    ]);

    return redirect()->route('peminjaman.index')
        ->with('setActiveMenu', '/peminjaman')
        ->with('success', 'Peminjaman berhasil diajukan.');
}

// store studio musik
    public function store2(Request $request){
    $request->validate([
        'studio_id' => 'required|exists:studio_musik,id',
        'tanggal_pinjam' => 'required|date_format:Y-m-d\TH:i',
        'tanggal_kembali' => [
            'required',
            'date_format:Y-m-d\TH:i',
            function ($attribute, $value, $fail) use ($request) {
                $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
                $tanggalKembali = Carbon::parse($value);
                if ($tanggalKembali->gt($tanggalPinjam->copy()->addDay())) {
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

    // Validasi alat yang sedang dipinjam pada waktu yang sama dan sudah disetujui
    $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
    $tanggalKembali = Carbon::parse($request->tanggal_kembali);

    $alatTerpakai = Peminjaman::where('status', 'Disetujui')
        ->where(function ($query) use ($tanggalPinjam, $tanggalKembali) {
            $query->whereBetween('tanggal_pinjam', [$tanggalPinjam, $tanggalKembali])
                  ->orWhereBetween('tanggal_kembali', [$tanggalPinjam, $tanggalKembali])
                  ->orWhere(function ($query2) use ($tanggalPinjam, $tanggalKembali) {
                      $query2->where('tanggal_pinjam', '<=', $tanggalPinjam)
                             ->where('tanggal_kembali', '>=', $tanggalKembali);
                  });
        })
        ->get();

    $alatTerpakaiIds = [];
    foreach ($alatTerpakai as $p) {
        $alatDalamPeminjaman = json_decode($p->alat_id, true);
        $alatTerpakaiIds = array_merge($alatTerpakaiIds, $alatDalamPeminjaman);
    }

    $alatConflict = array_intersect($alatTerpakaiIds, $request->alat_id);
    if (!empty($alatConflict)) {
        $alatConflictNames = alat_musik::whereIn('id', $alatConflict)->pluck('nama')->implode(', ');
        return redirect()->back()->withInput()->withErrors([
            'alat_id' => "Alat musik $alatConflictNames sudah dipinjam pada tanggal tersebut"
        ]);
    }

    // Simpan data peminjaman
    Peminjaman::create([
        'user_id' => Auth::id(),
        'studio_id' => $request->studio_id,
        'alat_id' => json_encode($request->alat_id),
        'tanggal_pinjam' => $tanggalPinjam,
        'tanggal_kembali' => $tanggalKembali,
        'alasan' => $request->alasan,
        'jaminan' => 'KTM',
        'status' => 'Menunggu',
    ]);

    return redirect()->route('peminjaman.index')
        ->with('setActiveMenu', '/peminjaman')
        ->with('success', 'Peminjaman berhasil diajukan.');
}

}
