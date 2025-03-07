@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Formulir Ajukan Peminjaman Studio Musik</h2>
    <div class="card p-4">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control bg-light" value="{{ $studios->nama }}" readonly>
            </div>

            <!-- Alat Musik (diambil dari tabel) -->
            <div class="mb-3">
                <label class="form-label fw-bold">Alat Musik</label>
                @if ($alats->isNotEmpty())
                    <div class="row">
                        @foreach ($alats as $alat)
                            <div class="col-md-4 col-sm-6"> <!-- Menyesuaikan ukuran grid -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="alat_musik[]" value="{{ $alat->id }}" id="alat{{ $alat->id }}">
                                    <label class="form-check-label" for="alat{{ $alat->id }}">
                                        {{ $alat->nama }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Tidak ada alat musik tersedia.</p>
                @endif
            </div>
                        

            <!-- Tanggal & Waktu Pemakaian -->
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal dan Waktu Pemakaian</label>
                <input type="datetime-local" class="form-control" name="tanggal_pinjam" required>
            </div>

            <!-- Tanggal & Waktu Kembali -->
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal dan Waktu Kembali</label>
                <input type="datetime-local" class="form-control" name="tanggal_kembali" required>
            </div>

            <!-- Alasan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Alasan</label>
                <textarea class="form-control" name="alasan" rows="3" required></textarea>
            </div>

            <!-- Jaminan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Jaminan</label>
                <input type="text" class="form-control bg-light" name="jaminan" value="KTM" readonly>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.mahasiswa') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
