@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Edit Akun Staf</h2>

    <div class="card shadow p-4">
        <form action="{{ route('akun_staf.update', $staf->id) }}" method="POST">
            @csrf
            @method('POST') {{-- Menggunakan metode POST dengan _method untuk PATCH --}}

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control" value="{{ $staf->nim }}" required>
                    @error('nim')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control" value="{{ $staf->nama }}" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Prodi</label>
                    <input type="text" name="prodi" class="form-control" value="{{ $staf->prodi }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $staf->email }}" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('akun_staf.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
