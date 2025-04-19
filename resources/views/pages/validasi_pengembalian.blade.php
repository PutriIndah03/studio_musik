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
    <style>
        /* .table-sm th, .table-sm td {
            padding: 0.3rem 0.4rem;
            font-size: 0.65rem;
            vertical-align: middle;
        } */
    </style>
    <h2 class="mb-4 text-center fw-bold">Validasi Pengembalian</h2>
    <div class="table-responsive"> 
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
                <th style="background-color: #0d6efd; color: white;">Kondisi Dipinjam</th>
                <th style="background-color: #0d6efd; color: white;">Kondisi Dikembalikan</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Kembali</th>
                <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Ket. Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Catatan Pengembalian</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengembalian as $index => $data)
            <tr>
                <td>{{ $loop->iteration + ($pengembalian->currentPage() - 1) * $pengembalian->perPage() }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->nama ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->nim ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->prodi ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->user->mahasiswa)->no_hp ?? '-' }}</td>
                <td>{{ optional($data->peminjaman->studio_musik)->nama ?? '-' }}</td>
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

                {{-- kondisi dikembalikan --}}
                <td style="text-align: left">
                    @if($data->kondisi)
                        @php
                            $kondisiAlat = json_decode($data->kondisi, true);
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
                    @if($data->status == 'Menunggu')
                        <span class="badge bg-warning">Menunggu</span>
                    @elseif($data->status == 'Diterima')
                        <span class="badge bg-success">Diterima</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('pengembalian.approve', $data->id) }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Terima pengembalian ini?');">
                            <i class="bi bi-check-circle"></i>
                        </button>
                    </form>

                    {{-- <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $data->id }}">
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
                    </div> --}}
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
    <div class="d-flex justify-content-center">
        {{ $pengembalian->links() }}
    </div>
</div>
@endsection
