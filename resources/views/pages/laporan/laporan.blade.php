@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Laporan Peminjaman</h2>

<form method="GET" action="{{ route('laporan') }}" class="mb-3">
    <div class="row g-2 align-items-end">
        {{-- Filter Tanggal --}}
        <div class="col-md-6 col-lg-4">
            <label class="form-label mb-1">Filter Tanggal Pinjam</label>
            <div class="d-flex align-items-center">
                <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request('start_date') }}">
                <span class="mx-2">s/d</span>
                <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request('end_date') }}">
                <button type="submit" class="btn btn-primary btn-sm ms-2">Filter</button>
            </div>
        </div>

        {{-- Kolom Kosong Tengah --}}
        <div class="col-md-3 col-lg-4"></div>

        {{-- Tombol Download --}}
        <div class="col-md-3 col-lg-4 text-end">
            <a href="{{ route('laporan.download', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                class="btn btn-success btn-sm">
                <i class="bi bi-download"></i> Download
            </a>
        </div>
    </div>
</form>

    <div class="table-responsive">
        <table class="table table-bordered table-sm small">
            <thead>
                <tr class="bg-primary text-white text-center">
                    <th style="background-color: #0d6efd; color: white;">No</th>
                    <th style="background-color: #0d6efd; color: white;">Nama</th>
                    <th style="background-color: #0d6efd; color: white;">Program Studi</th>
                    <th style="background-color: #0d6efd; color: white;">No HP</th>
                    <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                    <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                    <th style="background-color: #0d6efd; color: white;">Kondisi Dikembalikan</th>
                    <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                    <th style="background-color: #0d6efd; color: white;">Status</th>
                    <th style="background-color: #0d6efd; color: white;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                
                @forelse ($peminjaman as $data)
                <tr>
                    <td>{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
                    <td>{{ optional($data->user)->mahasiswa->nama ?? '-' }}</td>
                    {{-- <td>{{ optional($data->user)->mahasiswa->nim ?? '-' }}</td> --}}
                    <td>{{ optional($data->user)->mahasiswa->prodi ?? '-' }}</td>
                    <td>{{ optional($data->user)->mahasiswa->no_hp ?? '-' }}</td>
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
    
    {{-- kondisi dikembalikan --}}
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
                <td>{{ optional($data->pengembalian)->tanggal_pengembalian ? \Carbon\Carbon::parse($data->pengembalian->tanggal_pengembalian)->format('d-m-Y H:i') : '-' }}</td>
                    
                    <td>
                        @php
                            $statusColors = [
                                'Dikembalikan' => 'secondary',
                                'Disetujui' => 'success',
                                'Ditolak' => 'danger',
                                'Menunggu' => 'warning'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$data->status] ?? 'dark' }}">{{ $data->status }}</span>
                    </td>
                    <td>
                                        <!-- Tombol Detail -->
                 <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}">
                        <i class="bi bi-eye"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel{{ $data->id }}">Detail</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm small">
                                <tbody>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Kondisi Dipinjam</th>
                                        <td>
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
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pemakaian</th>
                                        <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Ket. Pengembalian</th>
                                        <td>
                                            @if(optional($data->pengembalian)->keterangan_pengembalian == 'Terlambat')
                                                <bold class="text-danger">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                                            @elseif(optional($data->pengembalian)->keterangan_pengembalian == 'Tepat Waktu')
                                                <bold class="text-success">{{ optional($data->pengembalian)->keterangan_pengembalian }}</bold>
                                            @else
                                                <span class="text-muted">{{ optional($data->pengembalian)->keterangan_pengembalian ?? '-' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Catatan Peminjaman</th>
                                        <td>{{ $data->alasan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Catatan Pengembalian</th>
                                        <td>{{ optional($data->pengembalian)->alasan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Jaminan</th>
                                        <td>{{ $data->jaminan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #0d6efd; color: white;">Detail</th>
                                        <td>{{ $data->pengembalian->detail ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
</div>
@endsection
