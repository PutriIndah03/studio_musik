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
    public function index()
{
    // Cek apakah user sudah login
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    // Ambil ID user yang sedang login
    $userId = Auth::id();

    // Ambil hanya data peminjaman milik user yang login dengan status "Dikembalikan"
    $peminjaman = peminjaman::with('studio_musik', 'pengembalian')
        ->where('user_id', $userId)
        ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ->paginate(5);

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat.riwayat_peminjaman_mhs', compact('peminjaman'));
}

public function index2()
{
    $peminjaman = peminjaman::with('user.mahasiswa','studio_musik', 'pengembalian')
      
        ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ->paginate(5);

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.riwayat.riwayat_peminjaman', compact('peminjaman'));
}

public function laporan()
{
    $peminjaman = peminjaman::with('user.mahasiswa','studio_musik', 'pengembalian')
      
        // ->where('status', 'Dikembalikan') // Filter hanya yang sudah dikembalikan
        ->paginate(5);

    foreach ($peminjaman as $item) {
        // Ubah JSON 'alat_id' menjadi array
        $alat_ids = json_decode($item->alat_id, true) ?? [];

        // Ambil data alat musik berdasarkan ID dalam array
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    return view('pages.laporan', compact('peminjaman'));
}

public function download(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
    }

    $user = Auth::user();

    // Ambil data peminjaman milik user yg statusnya 'Dikembalikan'
    $peminjaman = Peminjaman::with(['studio_musik', 'pengembalian'])
        ->where('user_id', $user->id)
        ->where('status', 'Dikembalikan')
        ->get();

    // Tambahkan data alat_musik dari field JSON 'alat_id'
    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    // Filter tanggal kalau ada
    if ($request->has('date') && $request->date != null) {
        $tanggal = $request->date;
    
        $peminjaman = $peminjaman->filter(function ($item) use ($tanggal) {
            return \Carbon\Carbon::parse($item->tanggal_pinjam)->toDateString() === $tanggal;
        })->values();
    }

    // Render view tabel seperti biasa
    $html = view('pages.riwayat_peminjaman_mhs', compact('peminjaman'))->render();

    // Ambil bagian <table> saja
    preg_match('/<table.*<\/table>/s', $html, $match);
    $tableOnlyHtml = $match[0] ?? '<p>Tidak ada data</p>';

    // Styling untuk PDF
    $fullHtml = '
        <h3 style="text-align: center; margin-bottom: 20px;">Riwayat Peminjaman</h3>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                text-align: left;
            }
            th {
                background-color: #f0f0f0;
            }
        </style>
        ' . $tableOnlyHtml;

    // Generate PDF
    $pdf = PDF::loadHTML($fullHtml);
    return $pdf->download('riwayat-peminjaman.pdf');
}


public function downloadRiwayatAdmin(Request $request)
{
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

public function downloadlaporan(Request $request)
{
    // Ambil data dari DB dulu
    $peminjaman = Peminjaman::with('user.mahasiswa', 'studio_musik', 'pengembalian')
        // ->where('status', 'Dikembalikan')
        ->get();

    // Tambahkan alat_musik ke setiap item
    foreach ($peminjaman as $item) {
        $alat_ids = json_decode($item->alat_id, true) ?? [];
        $item->alat_musik = alat_musik::whereIn('id', $alat_ids)->get();
    }

    // Filter berdasarkan tanggal jika ada
    if ($request->has('date') && $request->date != null) {
        $tanggal = $request->date;
    
        $peminjaman = $peminjaman->filter(function ($item) use ($tanggal) {
            return \Carbon\Carbon::parse($item->tanggal_pinjam)->toDateString() === $tanggal;
        })->values();
    }
    

    // Render view ke HTML
    $html = view('pages.laporan', compact('peminjaman'))->render();

    // Ambil hanya isi <table>
    preg_match('/<table.*<\/table>/s', $html, $match);
    $tableOnlyHtml = $match[0] ?? '<p>Tidak ada data</p>';

    // HTML lengkap untuk PDF
    $fullHtml = '
        <h3 style="text-align: center; margin-bottom: 20px;">Riwayat Peminjaman</h3>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10px;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
                text-align: left;
            }
            th {
                background-color: #f0f0f0;
            }
        </style>
    ' . $tableOnlyHtml;

    // Buat PDF dengan mode landscape
    $pdf = PDF::loadHTML($fullHtml)->setPaper('a4', 'landscape');

    return $pdf->download('laporan.pdf');
}

}
