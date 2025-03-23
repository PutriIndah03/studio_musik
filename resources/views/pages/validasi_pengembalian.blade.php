@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Validasi Pengembalian</h2>
    <table class="table table-bordered table-sm small">
        <thead>
            <tr class="bg-primary text-white">
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">NIM</th>
                <th style="background-color: #0d6efd; color: white;">Prodi</th>
                <th style="background-color: #0d6efd; color: white;">No HP</th>
                <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi Saat Dikembalikan</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Keterangan Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Alasan</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
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
                <td style="text-align: left">
                    @if($data->alat_musik instanceof Illuminate\Support\Collection)
                        @foreach($data->alat_musik as $alat)
                            {{ $alat->kode }} - {{ $alat->nama }} <br>
                        @endforeach
                    @elseif($data->alat_musik)
                        {{ $data->alat_musik->kode }} - {{ $data->alat_musik->nama }} <br>
                    @else
                        -
                    @endif
                </td>
                <td style="text-align: left">
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
                <td style="text-align: left">
                    @if($data->kondisi)
                        @php
                            $kondisiAlat = json_decode($data->kondisi, true);
                        @endphp
                
                        @if(is_array($kondisiAlat) && count($kondisiAlat) > 0)
                            @foreach($kondisiAlat as $alat_id => $kondisi)
                                @php
                                    $alat = \App\Models\alat_musik::find($alat_id);
                                @endphp
                                {{ $alat ? $alat->kode .' - '. $alat->nama : 'Alat Tidak Ditemukan' }} : {{ $kondisi ?? '-' }}<br>
                            @endforeach
                        @else
                            {{ $data->kondisi ?? '-' }}
                        @endif
                    @else
                        -
                    @endif
                </td>
                
                <td>{{ \Carbon\Carbon::parse($data->peminjaman->tanggal_kembali)->format('d-m-Y H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($data->tanggal_pengembalian)->format('d-m-Y H:i') }}</td>
                <td>
                    @if($data->keterangan_pengembalian == 'Terlambat')
                        <bold class="text-danger">{{ $data->keterangan_pengembalian }}</bold>
                    @elseif($data->keterangan_pengembalian == 'Tepat Waktu')
                        <bold class="text-success">{{ $data->keterangan_pengembalian }}</bold>
                    @else
                        <bold class="text-muted">{{ $data->keterangan_pengembalian ?? '-' }}</bold>
                    @endif
                </td>
                <td>{{ $data->alasan ?? '-' }}</td>
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
                    <form action="{{ route('pengembalian.approve', $data->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima pengembalian ini?');">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>

                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
                        <i class="bi bi-x-circle"></i>
                    </button>

                    <div class="modal fade" id="rejectModal{{ $data->id }}" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('pengembalian.reject', $data->id) }}" method="POST">
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
                <td colspan="15" class="text-center">Tidak ada data pengembalian</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
