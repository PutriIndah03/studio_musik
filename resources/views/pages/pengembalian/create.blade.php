@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Formulir Pengembalian Alat Musik & Studio</h2>
    <div class="card p-4">
        <form action="{{ route('peminjaman.prosesPengembalian') }}" method="POST">
            @csrf

            <!-- Kirim ID Peminjaman -->
            <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">

            <!-- Menampilkan Studio (jika ada) -->
            @if($studio_musik)
                <div class="mb-3">
                    <label class="form-label fw-bold">Studio Musik</label>
                    <input type="text" class="form-control bg-light" value="{{ $studio_musik->nama }}" readonly>
                </div>
            @endif

            <!-- Menampilkan Alat Musik -->
            <div class="mb-3">
                <label class="form-label fw-bold">Alat Musik</label>
                <div class="col-md-4 col-sm-6">
                    @if ($alat_musik && $alat_musik->count() > 0)
                        @foreach ($alat_musik as $alat)
                            <div class="form-check">
                                <input type="hidden" name="alat_id[]" value="{{ $alat->id }}">
                                <label class="form-check-label">
                                    {{ $alat->kode }} - {{ $alat->nama }} - {{ $alat->kondisi }}
                                </label>
                            </div>
                        @endforeach
                    @else
                        <p class="text-danger">Tidak ada alat musik yang dipinjam.</p>
                    @endif
                </div>
            </div>

            <!-- Tanggal & Waktu Pengembalian -->
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal dan Waktu Pengembalian</label>
                <input type="hidden" name="tanggal_pengembalian" value="{{ now()->format('Y-m-d H:i:s') }}">
                <input type="datetime-local" class="form-control bg-light" 
                    value="{{ now()->format('Y-m-d\TH:i') }}" readonly>
            </div>

            <!-- Status Keterlambatan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Keterangan Pengembalian</label>
                @php
                    $tanggalJatuhTempo = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                    $tanggalSekarang = \Carbon\Carbon::now();
                    $terlambat = $tanggalSekarang->greaterThan($tanggalJatuhTempo);
                @endphp
                <input type="hidden" name="keterangan_keterlambatan" value="{{ $terlambat ? 'Terlambat' : 'Tepat Waktu' }}">
                <input type="text" class="form-control {{ $terlambat ? 'text-danger' : 'text-success' }}" 
                    value="{{ $terlambat ? 'Terlambat' : 'Tepat Waktu' }}" readonly>
            </div>

            <!-- Kondisi Saat Dikembalikan -->
            {{-- <div class="mb-3">
                <label class="form-label">Kondisi Saat Dikembalikan</label>
                <select class="form-select" name="kondisi" required>
                    <option value="baik">Baik</option>
                    <option value="rusak ringan">Rusak ringan</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div> --}}

<!-- Menampilkan Alat Musik dan Kondisi Saat Dikembalikan -->
<div class="mb-3">
    <label class="form-label fw-bold">Alat Musik & Kondisi Saat Dikembalikan</label>
    @if ($alat_musik && $alat_musik->count() > 0)
        @foreach ($alat_musik as $alat)
            <div class="mb-2 p-2 border rounded">
                <!-- Hidden ID Alat Musik -->
                <input type="hidden" name="alat_id[]" value="{{ $alat->id }}">

                <!-- Nama Alat Musik -->
                <p class="fw-bold mb-1">{{ $alat->kode }} - {{ $alat->nama }}</p>

                <!-- Dropdown Kondisi -->
                <select class="form-select" name="kondisi[{{ $alat->id }}]" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak Ringan">Rusak ringan</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>
        @endforeach
    @else
        <p class="text-danger">Tidak ada alat musik yang dipinjam.</p>
    @endif
</div>


            <!-- Catatan Tambahan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Tambahan</label>
                <textarea class="form-control" name="alasan" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('peminjaman.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
