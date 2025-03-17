@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Validasi Peminjaman</h2>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">NIM</th>
                <th style="background-color: #0d6efd; color: white;">Prodi</th>
                <th style="background-color: #0d6efd; color: white;">No HP</th>
                <th style="background-color: #0d6efd; color: white;">Yang Dipinjam</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Jumlah</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Tanggal & Waktu Pemakaian</th>
                <th style="background-color: #0d6efd; color: white;">Tanggal & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Alasan</th>
                <th style="background-color: #0d6efd; color: white;">Jaminan</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->user->nama ?? '-' }}</td>
                <td>{{ $data->user->nim ?? '-' }}</td>
                <td>{{ $data->user->prodi ?? '-' }}</td>
                <td>{{ $data->user->no_hp ?? '-' }}</td>
                <td>{{ $data->studio_musik ? 'Studio Musik' : 'Alat Musik' }}</td>
                <td>
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        {{ $data->alat_musik->pluck('nama')->implode(', ') }}
                    @elseif($data->alat_musik)
                        {{ optional($data->alat_musik)->nama }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $data->jumlah ?? '-' }}</td>
                <td>
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @foreach($data->alat_musik as $alat)
                            {{ $alat->nama }}: {{ $alat->kondisi ?? '-' }}<br>
                        @endforeach
                    @elseif($data->alat_musik)
                        {{ optional($data->alat_musik)->kondisi ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>
                <td>{{ $data->alasan ?? '-' }}</td>
                <td>{{ $data->jaminan ?? '-' }}</td>
                <td>
                    @if($data->status == 'menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif($data->status == 'disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($data->status == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Dikembalikan</span>
                    @endif
                </td>
                <td>
                    <a href="" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="15" class="text-center">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
