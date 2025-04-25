@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Riwayat Peminjaman</h2>

    <form method="GET" action="{{ route('riwayatPeminjamanMhs') }}" class="mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="date" class="form-label">Filter Tanggal Pinjam</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="{{ request('date') }}"
                    onchange="this.form.submit()">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <!-- Optional: you can remove this button since it's auto-submit -->
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('riwayatPeminjamanMhs.download', ['date' => request('date')]) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </form>
      
    <div class="table-responsive"> <!-- Tambahkan class ini -->
        <table class="table table-bordered table-sm small">
            <thead>
                <tr class="bg-primary text-white">
                    <th style="background-color: #0d6efd; color: white;">No</th>
                    <th style="background-color: #0d6efd; color: white;">Studio Musik</th>
                    <th style="background-color: #0d6efd; color: white;">Alat Musik</th>
                    <th style="background-color: #0d6efd; color: white;">Kondisi Dipinjam</th>
                    <th style="background-color: #0d6efd; color: white;">Kondisi Dikembalikan</th>
                    <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pemakaian</th>
                    <th style="background-color: #0d6efd; color: white;">Tgl & Waktu Pengembalian</th>
                    <th style="background-color: #0d6efd; color: white;">Ket. Pengembalian</th>
                    <th style="background-color: #0d6efd; color: white;">Catatan Peminjaman</th>
                    <th style="background-color: #0d6efd; color: white;">Catatan Pengembalian</th>
                    <th style="background-color: #0d6efd; color: white;">Jaminan</th>
                    <th style="background-color: #0d6efd; color: white;">Detail</th>
                    <th style="background-color: #0d6efd; color: white;">Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $filteredPeminjaman = $peminjaman->where('status', 'Dikembalikan');
                    if (request('date')) {
                        $filteredPeminjaman = $filteredPeminjaman->filter(function ($item) {
                            return \Carbon\Carbon::parse($item->tanggal_pinjam)->toDateString() == request('date');
                        });
                    }
                @endphp

                @forelse ($filteredPeminjaman as $index => $data)
                <tr>
                    <td>{{ $loop->iteration + ($peminjaman->currentPage() - 1) * $peminjaman->perPage() }}</td>
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

                    <td style="text-align: left">
                        @if($data->pengembalian && $data->pengembalian->kondisi)
                            @php
                                $kondisiAlat = json_decode($data->pengembalian->kondisi, true);
                            @endphp
                    
                            @if(is_array($kondisiAlat) && count($kondisiAlat) > 1)
                                @php $nomor = 1; @endphp
                                @foreach($kondisiAlat as $alat_id => $kondisi)
                                    {{ $nomor++ }}. {{ $kondisi ?? '-' }}<br>
                                @endforeach
                            @elseif(is_array($kondisiAlat) && count($kondisiAlat) == 1)
                                {{ reset($kondisiAlat) ?? '-' }}
                            @else
                                {{ $data->pengembalian->kondisi ?? '-' }}
                            @endif
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ \Carbon\Carbon::parse($data->tanggal_pinjam)->format('d-m-Y H:i') }}</td>
                    <td>{{ optional($data->pengembalian)->tanggal_pengembalian ? \Carbon\Carbon::parse($data->pengembalian->tanggal_pengembalian)->format('d-m-Y H:i') : '-' }}</td>

                    <td>
                        @if(optional($data->pengembalian)->keterangan_pengembalian == 'Terlambat')
                            <span class="text-danger fw-bold">{{ optional($data->pengembalian)->keterangan_pengembalian }}</span>
                        @elseif(optional($data->pengembalian)->keterangan_pengembalian == 'Tepat Waktu')
                            <span class="text-success fw-bold">{{ optional($data->pengembalian)->keterangan_pengembalian }}</span>
                        @else
                            <span class="text-muted">{{ optional($data->pengembalian)->keterangan_pengembalian ?? '-' }}</span>
                        @endif
                    </td>
                    <td>{{ $data->alasan ?? '-' }}</td>
                    <td>{{ optional($data->pengembalian)->alasan ?? '-' }}</td>
                    <td>{{ $data->jaminan ?? '-' }}</td>
                    <td>{{ optional($data->pengembalian)->detail ?? '-' }}</td>
                    <td><span class="badge bg-secondary">Dikembalikan</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="12" class="text-center">Tidak ada riwayat peminjaman</td>
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
