<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;

class JadwalPeminjamanController extends Controller
{
    public function index()
    {
        // Ambil data peminjaman dengan status "Disetujui" dan relasi studio serta alat musik
        $peminjaman = Peminjaman::with('studio_musik')->where('status', 'Disetujui')->get();
    
        // Format data untuk FullCalendar
        $events = [];
        foreach ($peminjaman as $item) {
            // Ambil nama studio (jika ada)
            $studioNama = $item->studio_musik->nama ?? null;
    
            // Ambil daftar alat musik beserta kodenya
            $alat_ids = json_decode($item->alat_id, true) ?? [];
            $alat_list = alat_musik::whereIn('id', $alat_ids)->get(['nama', 'kode']);
    
            // Format alat musik menjadi "Nama Alat (Kode)"
            $alat_nama_str = $alat_list->map(fn($alat) => "{$alat->nama} ({$alat->kode})")->implode(', ');
    
            // Buat title dengan atau tanpa studio
            $title = $studioNama ? "$studioNama | $alat_nama_str" : $alat_nama_str;
    
            // Menambahkan data ke kalender
            $events[] = [
                'title' => $title,
                'start' => $item->tanggal_pinjam,
                'end'   => $item->tanggal_kembali,
                'backgroundColor' => $studioNama ? '#007bff' : '#28a745', // Biru untuk studio, hijau untuk alat
                'borderColor' => '#000',
            ];
        }
    
        return view('pages.jadwal_peminjaman', compact('events'));
    }
    
}
