@extends('layouts.app')

@section('content')
<div class="container mt-4">
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
        }, 5000);
    </script>
    @endif
    <h2 class="mb-4 text-center fw-bold">Validasi Peminjaman</h2>
    <table class="table table-bordered table-sm small">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">NIM</th>
                <th style="background-color: #0d6efd; color: white;">Program Studi</th>
                <th style="background-color: #0d6efd; color: white;">No HP</th>
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
                <td>{{ optional($data->user->mahasiswa)->nama ?? '-' }}</td>
                <td>{{ optional($data->user->mahasiswa)->nim ?? '-' }}</td>
                <td>{{ optional($data->user->mahasiswa)->prodi ?? '-' }}</td>
                <td>{{ optional($data->user->mahasiswa)->no_hp ?? '-' }}</td>
                                                             
                <td>{{ optional($data->studio_musik)->nama ?? '-' }}</td>
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
                </td>
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
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_kembali)->format('d-m-Y H:i') }}</td>
                <td>{{ $data->alasan ?? '-' }}</td>
                <td>{{ $data->jaminan ?? '-' }}</td>
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
                <td>
                    <!-- Tombol Setujui -->
                    <form action="{{ route('peminjaman.approve', $data->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?');">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>
                
                    <!-- Tombol Tolak (Menampilkan Modal) -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
                        <i class="bi bi-x-circle"></i>
                    </button>

                    <!-- Modal Alasan Penolakan -->
                    <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectModalLabel{{ $data->id }}">Alasan Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('peminjaman.reject', $data->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="detail-{{ $data->id }}" class="form-label">Alasan Penolakan</label>
                                            <textarea name="detail" id="detail-{{ $data->id }}" class="form-control" rows="4" required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>
                                  
            </tr>
            @empty
            <tr>
                <td colspan="15" class="text-center">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $peminjaman->links() }}
    </div>
</div>
@endsection
