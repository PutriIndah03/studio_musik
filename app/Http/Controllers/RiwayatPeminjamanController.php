<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RiwayatPeminjamanController extends Controller
{

    //riwayat peminjaman mahasiswa
    public function index(Request $request)
{
    // Cek apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    // Ambil ID user yang sedang login
    $userId = Auth::id();

    // Ambil hanya data peminjaman milik user yang login dengan status "Dikembalikan"
    $query = peminjaman::with('studio_musik', 'pengembalian')
        ->where('user_id', $userId)
        ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ;

        if ($request->filled('date')) {
            try {
                $date = Carbon::parse($request->date)->toDateString();
                $query->whereDate('tanggal_pinjam', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
            }
        }
    
        $peminjaman = $query->paginate(5);

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat.riwayat_peminjaman_mhs', compact('peminjaman'));
}


// riwayat peminjaman staf dan pembina
public function index2(Request $request)
{
    $query = peminjaman::with('user.mahasiswa','studio_musik', 'pengembalian')
      
        ->where('status', 'Dikembalikan');

        if ($request->filled('date')) {
            try {
                $date = Carbon::parse($request->date)->toDateString();
                $query->whereDate('tanggal_pinjam', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
            }
        }
    
        $peminjaman = $query->paginate(5);

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat.riwayat_peminjaman', compact('peminjaman'));
}

public function laporan(Request $request)
{
    $query = peminjaman::with('user.mahasiswa','studio_musik', 'pengembalian');

    if ($request->filled('date')) {
        try {
            $date = Carbon::parse($request->date)->toDateString();
            $query->whereDate('tanggal_pinjam', $date);
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
        }
    }

    $peminjaman = $query->paginate(5);

    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.laporan.laporan', compact('peminjaman'));
}

// download riwayat peminjaman mahasiswa
public function download(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $userId = Auth::id();

    $query = peminjaman::with('studio_musik', 'pengembalian')
        ->where('user_id', $userId)
        ->where('status', 'Dikembalikan');

        if ($request->filled('date')) {
            try {
                $date = Carbon::parse($request->date)->toDateString();
                $query->whereDate('tanggal_pinjam', $date);
            } catch (\Exception $e) {
                return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
            }
        }

    $peminjaman = $query->get();

    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    $pdf = Pdf::loadView('pages.riwayat.downloadMhs', [
        'peminjaman' => $peminjaman,
        'date' => $request->date ?? null
    ])->setPaper('A4', 'landscape');

    return $pdf->download('riwayat_peminjaman_mahasiswa.pdf');
}

// download riwayat peminjaman staf dan pembina
public function downloadRiwayatAdmin(Request $request){
    $query = Peminjaman::with(['user.mahasiswa', 'studio_musik', 'pengembalian'])
        ->where('status', 'Dikembalikan')
        ->orderBy('id');

    // Filter berdasarkan tanggal jika disediakan
    if ($request->filled('date')) {
        try {
            $date = Carbon::parse($request->date)->toDateString();
            $query->whereDate('tanggal_pinjam', $date);
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
        }
    }

    $peminjaman = $query->get();

    // Ambil semua ID alat dari semua peminjaman
    $allAlatIds = $peminjaman->pluck('alat_id')
        ->map(fn($id) => json_decode($id, true))
        ->flatten()
        ->unique()
        ->filter()
        ->values();

    // Ambil semua data alat musik sekali saja
    $alatMusikMap = alat_musik::whereIn('id', $allAlatIds)->get()->keyBy('id');

    // Tambahkan data alat musik ke masing-masing peminjaman
    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = collect($alat_ids)->map(fn($id) => $alatMusikMap[$id] ?? null)->filter();
    }

    // Load PDF view
    $pdf = Pdf::loadView('pages.riwayat.download', [
        'peminjaman' => $peminjaman,
        'date' => $request->date
    ])->setPaper('a4', 'landscape');

    return $pdf->download('riwayat_peminjaman.pdf');
}


public function downloadLaporan(Request $request)
{
    $query = peminjaman::with('user.mahasiswa', 'studio_musik', 'pengembalian');

    // Opsional: filter hanya yang sudah dikembalikan
    // $query->where('status', 'Dikembalikan');

    // Filter berdasarkan tanggal jika diberikan
    if ($request->filled('date')) {
        try {
            $date = Carbon::parse($request->date)->toDateString();
            $query->whereDate('tanggal_pinjam', $date);
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Format tanggal tidak valid.']);
        }
    }

    $peminjaman = $query->get();

    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    $pdf = Pdf::loadView('pages.laporan.download', [
        'peminjaman' => $peminjaman,
        'date' => $request->date
    ])->setPaper('a4', 'landscape');

    return $pdf->download('laporan_peminjaman.pdf');
}
}
