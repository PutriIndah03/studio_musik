@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Tambah Alat Musik</h2>

    <div class="card shadow p-4">
        <form action="{{ route('alat_musik.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Kode -->
            <div class="mb-3">
                <label for="kode" class="form-label fw-bold">Kode</label>
                <input type="text" class="form-control" id="kode" name="kode" required>
                @error('kode')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <!-- Tipe -->
            <div class="mb-3">
                <label for="tipe" class="form-label fw-bold">Tipe</label>
                <input type="text" class="form-control" id="tipe" name="tipe" required>
            </div>

            <!-- Gambar -->
            <div class="mb-3">
                <label for="foto" class="form-label fw-bold">Gambar</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
            </div>

            <!-- Jumlah -->
            <div class="mb-3">
                <label for="jumlah" class="form-label fw-bold">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" required>
            </div>

            <!-- Kondisi -->
            <div class="mb-3">
                <label for="kondisi" class="form-label fw-bold">Kondisi</label>
                <select class="form-select" id="kondisi" name="kondisi" required>
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Tidak Tersedia">Tidak Tersedia</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('alat_musik.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection
