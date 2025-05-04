<!DOCTYPE html>
<html>
<head>
    <title>Laporan</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <h3 style="text-align: center;">Laporan</h3>
    @if($date)
        <p>Filter Tanggal: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>No HP</th>
                <th>Studio Musik</th>
                <th>Alat Musik</th>
                <th>Kondisi Dipinjam</th>
                <th>Kondisi Dikembalikan</th>
                <th>Tgl & Waktu Pemakaian</th>
                <th>Tgl & Waktu Pengembalian</th>
                <th>Ket. Pengembalian</th>
                <th>Catatan Peminjaman</th>
                <th>Catatan Pengembalian</th>
                <th>Jaminan</th>
                <th>Detail</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ optional($data->user)->mahasiswa->nama ?? '-' }}</td>
                <td>{{ optional($data->user)->mahasiswa->nim ?? '-' }}</td>
                <td>{{ optional($data->user)->mahasiswa->prodi ?? '-' }}</td>
                <td>{{ optional($data->user)->mahasiswa->no_hp ?? '-' }}</td>
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
                <td>
                    @if(isset($data->alat_musik) && count($data->alat_musik))
                        @foreach($data->alat_musik as $index => $alat)
                            @if(count($data->alat_musik) > 1){{ $index + 1 }}. @endif{{ $alat->nama ?? '-' }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(isset($data->alat_musik) && count($data->alat_musik))
                        @foreach($data->alat_musik as $index => $alat)
                            @if(count($data->alat_musik) > 1){{ $index + 1 }}. @endif{{ $alat->kondisi ?? '-' }}<br>
                        @endforeach
                    @else
                        -
                    @endif
                </td>
                <td>
                    @php
                        $kondisiAlat = $data->pengembalian ? json_decode($data->pengembalian->kondisi, true) : null;
                    @endphp
                    @if(is_array($kondisiAlat))
                        @foreach(array_values($kondisiAlat) as $index => $kondisi)
                            @if(count($kondisiAlat) > 1){{ $index + 1 }}. @endif{{ $kondisi ?? '-' }}<br>
                        @endforeach
                    @else
                        {{ $data->pengembalian->kondisi ?? '-' }}
                    @endif
                </td>
                
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                <td>{{ optional($data->pengembalian)->tanggal_pengembalian ? \Carbon\Carbon::parse($data->pengembalian->tanggal_pengembalian)->format('d-m-Y H:i') : '-' }}</td>
                <td>{{ optional($data->pengembalian)->keterangan_pengembalian ?? '-' }}</td>
                <td>{{ $data->alasan ?? '-' }}</td>
                <td>{{ optional($data->pengembalian)->alasan ?? '-' }}</td>
                <td>{{ $data->jaminan ?? '-' }}</td>
                <td>{{ optional($data->pengembalian)->detail ?? '-' }}</td>
                <td>{{ $data->status ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="17" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>


