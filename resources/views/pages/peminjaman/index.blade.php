@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Peminjaman</h2>
    <table class="table table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Tanggal & Waktu Pemakaian</th>
                <th style="background-color: #0d6efd; color: white;">Tanggal & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Alasan</th>
                <th style="background-color: #0d6efd; color: white;">Jaminan</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                
                <!-- Studio Musik -->
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
                
                <!-- Alat Musik -->
                <td>
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        {{ $data->alat_musik->pluck('nama')->implode(', ') }}
                    @elseif($data->alat_musik)
                        {{ optional($data->alat_musik)->nama }}
                    @else
                        -
                    @endif
                </td>
                
                <!-- Kondisi Alat Musik -->
                <td>
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @foreach($data->alat_musik as $alat)
                            {{ $alat->nama }} : {{ $alat->kondisi ?? '-' }}<br>
                        @endforeach
                    @elseif($data->alat_musik)
                        {{ optional($data->alat_musik)->kondisi ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                
                <!-- Tanggal & Waktu Pemakaian -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                
                <!-- Tanggal & Waktu Kembali -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>
                
                <!-- Alasan -->
                <td>{{ $data->alasan ?? '-' }}</td>
                
                <!-- Jaminan -->
                <td>{{ $data->jaminan ?? '-' }}</td>
                
                <!-- Status -->
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
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
