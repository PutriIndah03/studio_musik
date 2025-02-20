@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Edit Studio Musik</h2>

    <div class="card shadow p-4">
        <form action="{{ route('studio_musik.update', $studio->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-3">
                <label for="nama" class="form-label fw-bold">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ $studio->nama }}" required>
            </div>

            <!-- Upload Foto -->
            <div class="mb-3">
                <label for="foto" class="form-label fw-bold">Upload Foto</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                @if($studio->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $studio->foto) }}" alt="Foto Studio" class="img-thumbnail" width="200">
                    </div>
                @endif
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="Tersedia" {{ $studio->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Tidak Tersedia" {{ $studio->status == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('studio_musik.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
