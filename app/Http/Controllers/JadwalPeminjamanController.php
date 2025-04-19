<?php

namespace App\Http\Controllers;

use App\Models\alat_musik;
use App\Models\peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalPeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman = Peminjaman::with('studio_musik')->where('status', 'Disetujui')->get();

        $eventsPerDate = [];

        foreach ($peminjaman as $item) {
            $tanggal = Carbon::parse($item->tanggal_pinjam)->toDateString();

            // Cek studio musik
            $studioNama = $item->studio_musik->nama ?? null;

            // Cek alat musik
            $alatIds = json_decode($item->alat_id, true) ?? [];
            $alatList = alat_musik::whereIn('id', $alatIds)->get(['nama']);
            $alatText = $alatList->map(fn($alat) => "{$alat->nama}")->implode(', ');

            // Tentukan isi entry sesuai aturan:
            if ($studioNama && !$alatText) {
                // Jika hanya studio musik yang dipinjam, tampilkan studio musiknya saja
                $entry = $studioNama;
            } elseif ($alatText && !$studioNama) {
                // Jika hanya alat musik yang dipinjam, tampilkan alat musiknya saja
                $entry = $alatText;
            } elseif ($studioNama && $alatText) {
                // Jika studio musik dan alat musik dipinjam, tampilkan hanya studio musiknya saja
                $entry = $studioNama;
            } else {
                $entry = '-';  // Tidak ada data yang valid
            }

            if (!isset($eventsPerDate[$tanggal])) {
                $eventsPerDate[$tanggal] = [];
            }

            // Pastikan hanya satu entry yang muncul per tanggal
            if (!in_array($entry, $eventsPerDate[$tanggal])) {
                $eventsPerDate[$tanggal][] = $entry;
            }
        }

        // Format untuk FullCalendar
        $calendarEvents = [];

        foreach ($eventsPerDate as $tanggal => $list) {
            $formattedTitle = implode('<br>', $list);  // Gabungkan tanpa penomoran berulang
            $calendarEvents[] = [
                'title' => $formattedTitle,
                'start' => $tanggal,
                'end' => Carbon::parse($tanggal)->addDay()->toDateString(),
                'display' => 'background',
                'backgroundColor' => '#FEBA17', // Warna latar belakang
                'textColor' => '#000000', // Warna teks menjadi hitam
                'extendedProps' => [
                    'style' => 'font-size: 5px; color: black;' // Menurunkan ukuran font dan menjadikan warna teks hitam
                ],
            ];
            
        }

        return view('pages.jadwal_peminjaman', compact('calendarEvents'));
    }
}
