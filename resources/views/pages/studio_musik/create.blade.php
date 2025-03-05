@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Tambah Studio Musik</h2>

    <div class="card shadow p-4">
        <form action="{{ route('studio_musik.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>

            <!-- Upload Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label fw-bold">Upload Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
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
                <a href="{{ route('studio_musik.index') }}" class="btn btn-danger">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            
            </div>
        </form>
    </div>
</div>
@endsection