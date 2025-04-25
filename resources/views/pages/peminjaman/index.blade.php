@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Peminjaman</h2>
    
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

    <table class="table table-bordered table-sm small">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pemakaian</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Catatan Penggunaan</th>
                <th style="background-color: #0d6efd; color: white;">Jaminan</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjaman as $index => $data)
            <tr>
                <td>{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
                
                <!-- Studio Musik -->
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
                
                <!-- Alat Musik -->
                <td style="text-align: left">
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @if($data->alat_musik->count() > 1)
                            @foreach($data->alat_musik as $loopIndex => $alat)
                                {{ $loop->iteration }}. {{ $alat->nama }} <br>
                            @endforeach
                        @elseif($data->alat_musik->count() == 1)
                            @php $alat = $data->alat_musik->first(); @endphp
                            {{ $alat->nama }}
                        @endif
                    @elseif($data->alat_musik)
                        {{ $data->alat_musik->nama }}
                    @else
                        -
                    @endif
                </td
                
                <!-- Kondisi Alat Musik -->
                <td style="text-align: left">
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @if($data->alat_musik->count() > 1)
                            @php $nomor = 1; @endphp
                            @foreach($data->alat_musik as $alat)
                                {{ $nomor++ }}. {{ $alat->kondisi ?? '-' }}<br>
                            @endforeach
                        @elseif($data->alat_musik->count() == 1)
                            @php $alat = $data->alat_musik->first(); @endphp
                            {{ $alat->kondisi ?? '-' }}
                        @endif
                    @elseif($data->alat_musik)
                        {{ $data->alat_musik->kondisi ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                
                <!-- Tanggal & Waktu Pemakaian -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                
                <!-- Tanggal & Waktu Kembali -->
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>

                {{-- locale('id')->translatedFormat('d F Y') --}}
                
                <!-- Alasan -->
                <td>{{ $data->alasan ?? '-' }}</td>
                
                <!-- Jaminan -->
                <td>{{ $data->jaminan ?? '-' }}</td>
                
                <!-- Status -->
                <td>
                    @if($data->status == 'Menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif($data->status == 'Disetujui')
                        <span class="badge bg-success">Disetujui</span>
                    @elseif($data->status == 'Ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Dikembalikan</span>
                    @endif
                </td>

<!-- Aksi -->
<td>
    @if($data->status == 'Disetujui')
        <a href="{{ route('peminjaman.formPengembalian', $data->id) }}" class="btn btn-secondary btn-sm text-white"
           onclick="return confirm('Ajukan pengembalian peminjaman ini?');">
            Ajukan pengembalian
        </a>
    @elseif($data->status == 'Ditolak')
        <!-- Tombol untuk membuka modal -->
        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#alasanModal{{ $data->id }}">
            Lihat Alasan
        </button>

        <!-- Modal -->
        <div class="modal fade" id="alasanModal{{ $data->id }}" tabindex="-1" aria-labelledby="alasanModalLabel{{ $data->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="alasanModalLabel{{ $data->id }}">Alasan Penolakan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $data->detail ?? 'Tidak ada alasan tersedia.' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <span class="text-muted">-</span>
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
    <div class="d-flex justify-content-center">
        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
