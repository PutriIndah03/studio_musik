@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Pengembalian</h2>

    @if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function () {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000); // Hilang dalam 5 detik
    </script>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Ket.Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Alasan</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalian as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                
                <!-- Studio Musik -->
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
                
                <!-- Alat Musik -->
                <td style="text-align: left">
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @if($data->alat_musik->count() > 1)
                            @foreach($data->alat_musik as $loopIndex => $alat)
                                {{ $loop->iteration }}. {{ $alat->kode }} - {{ $alat->nama }} <br>
                            @endforeach
                        @elseif($data->alat_musik->count() == 1)
                            @php $alat = $data->alat_musik->first(); @endphp
                            {{ $alat->kode }} - {{ $alat->nama }}
                        @endif
                    @elseif($data->alat_musik)
                        {{ $data->alat_musik->kode }} - {{ $data->alat_musik->nama }}
                    @else
                        -
                    @endif
                </td>
                
                
                <!-- Tanggal & Waktu Kembali -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>

                <!-- Tanggal & Waktu Pengembalian -->
                <td>{{ optional($data->pengembalian)->tanggal_pengembalian ? \Carbon\Carbon::parse($data->pengembalian->tanggal_pengembalian)->format('d-m-Y H:i') : '-' }}</td>

                <!-- Keterangan Pengembalian -->
                <td>
                    @if(optional($data->pengembalian)->keterangan_pengembalian == 'Terlambat')
                        <bold class="text-danger">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                    @elseif(optional($data->pengembalian)->keterangan_pengembalian == 'Tepat Waktu')
                        <bold class="text-success">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                    @else
                        <span class="text-muted">{{ optional($data->pengembalian)->keterangan_pengembalian ?? '-' }}</span>
                    @endif
                </td>
                

                <!-- Kondisi Alat Musik -->
                <td style="text-align: left">
                    @if($data->pengembalian && $data->pengembalian->kondisi)
                        @php
                            $kondisiAlat = json_decode($data->pengembalian->kondisi, true);
                        @endphp
                
                        @if(is_array($kondisiAlat) && count($kondisiAlat) > 1)
                            @php $nomor = 1; @endphp
                            @foreach($kondisiAlat as $alat_id => $kondisi)
                                @php
                                    $alat = $data->alat_musik->firstWhere('id', $alat_id) ?? null;
                                @endphp
                                {{ $nomor++ }}. {{ $kondisi ?? '-' }}<br>
                            @endforeach
                        @elseif(is_array($kondisiAlat) && count($kondisiAlat) == 1)
                            @php
                                $alat_id = array_key_first($kondisiAlat);
                                $alat = $data->alat_musik->firstWhere('id', $alat_id) ?? null;
                            @endphp
                            {{ reset($kondisiAlat) ?? '-' }}
                        @else
                            {{ $data->pengembalian->kondisi ?? '-' }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                
                <!-- Alasan -->
                <td>{{ optional($data->pengembalian)->alasan ?? '-' }}</td>

                <!-- Status Pengembalian -->
                <td>
                    @if(optional($data->pengembalian)->status == 'menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif(optional($data->pengembalian)->status == 'disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif(optional($data->pengembalian)->status == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Dikembalikan</span>
                    @endif
                </td>
                
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
