@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Edit Alat Musik</h2>

    <div class="card shadow p-4">
        <form action="{{ route('alat_musik.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Kode -->
            <div class="mb-3">
                <label for="kode" class="form-label fw-bold">Kode</label>
                <input type="text" class="form-control" id="kode" name="kode" value="{{ old('kode', $alat->kode) }}" required>
                @error('kode')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $alat->nama) }}" required>
            </div>

            {{-- <!-- Tipe -->
            <div class="mb-3">
                <label for="tipe" class="form-label fw-bold">Tipe</label>
                <input type="text" class="form-control" id="tipe" name="tipe" value="{{ old('tipe', $alat->tipe) }}" required>
            </div> --}}

            <!-- Gambar -->
            <div class="mb-3">
                <label for="foto" class="form-label fw-bold">Gambar</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                @if ($alat->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $alat->foto) }}" alt="{{ $alat->nama }}" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                @endif
            </div>

            <!-- Jumlah -->
            {{-- <div class="mb-3">
                <label for="jumlah" class="form-label fw-bold">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ old('jumlah', $alat->jumlah) }}" required>
            </div> --}}

            <!-- Kondisi -->
            <div class="mb-3">
                <label for="kondisi" class="form-label fw-bold">Kondisi</label>
                <select class="form-select" id="kondisi" name="kondisi" required>
                    <option value="Baik" {{ old('kondisi', $alat->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                    <option value="Rusak Ringan" {{ old('kondisi', $alat->kondisi) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="Rusak" {{ old('kondisi', $alat->kondisi) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Tersedia" {{ $alat->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Tidak Tersedia" {{ $alat->status == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('alat_musik.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
