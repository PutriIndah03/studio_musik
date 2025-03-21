@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Validasi Pengembalian</h2>
    <table class="table table-bordered table-sm small">
        <thead>
            <tr class="bg-primary text-white">
                <th>No</th>
                <th>Nama</th>
                <th>NIM</th>
                <th>Prodi</th>
                <th>No HP</th>
                <th>Studio Musik</th>
                <th>Alat Musik</th>
                <th>Kondisi Saat Dikembalikan</th>
                <th>Tanggal & Waktu Kembali</th>
                <th>Keterangan Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalian as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->nama ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->nim ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->prodi ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->no_hp ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->studio_musik)->nama ?? '-' }}</td>
                <td>
                    @if($data->peminjaman->alat_musik instanceof Illuminate\Support\Collection)
                        @foreach($data->peminjaman->alat_musik as $alat)
                            {{ $alat->kode }} - {{ $alat->nama }} <br>
                        @endforeach
                    @elseif($data->peminjaman->alat_musik)
                        {{ $data->peminjaman->alat_musik->kode }} - {{ $data->peminjaman->alat_musik->nama }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: left">
                    @if($data->pengembalian && $data->pengembalian->kondisi)
                        @php
                            $kondisiAlat = json_decode($data->pengembalian->kondisi, true);
                        @endphp
                
                        @if(is_array($kondisiAlat))
                            @foreach($kondisiAlat as $alat_id => $kondisi)
                                @php
                                    $alat = \App\Models\alat_musik::find($alat_id);
                                @endphp
                                {{ $alat ? $alat->kode .' - '. $alat->nama : 'Alat Tidak Ditemukan' }} : {{ $kondisi ?? '-' }}<br>
                            @endforeach
                        @else
                            {{ $data->pengembalian->kondisi ?? '-' }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengembalian)->format('d-m-Y H:i') }}</td>
                <td>{{ $data->keterangan_pengembalian ?? '-' }}</td>
                <td>
                    @if($data->status == 'menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif($data->status == 'diterima')
                        <span class="badge bg-success">Diterima</span>
                    @elseif($data->status == 'ditolak')
                        <span class="badge bg-danger">Ditolak</span>
                    @else
                        <span class="badge bg-secondary">Selesai</span>
                    @endif
                </td>
                <td>
                    <!-- Tombol Terima Pengembalian -->
                    <form action="" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima pengembalian ini?');">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>

                    <!-- Tombol Tolak (Menampilkan Modal) -->
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
                        <i class="bi bi-x-circle"></i>
                    </button>

                    <!-- Modal Edit Alasan Penolakan -->
                    <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="alasan-{{ $data->id }}" class="form-label">Alasan Penolakan</label>
                                            <textarea name="alasan" id="alasan-{{ $data->id }}" class="form-control" required>{{ $data->alasan }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak Pengembalian</button>
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
                <td colspan="12" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
