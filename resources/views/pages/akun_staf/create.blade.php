@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Tambah Akun Staf</h2>

    <div class="card shadow p-4">
        <form action="{{ Route('akun_staf.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" required>
                @error('nim')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
        </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Prodi</label>
                    <select name="prodi" class="form-select" required>
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <option value="Teknologi Rekayasa Perangkat Lunak">Teknologi Rekayasa Perangkat Lunak</option>
                        <option value="Manajemen Bisnis Pariwisata">Manajemen Bisnis Pariwisata</option>
                        <option value="Bahasa Inggris Mesin">Teknik Mesin</option>
                        <option value="Teknologi Rekayasa Komputer">Teknologi Rekayasa Komputer</option>
                        <option value="Teknik Sipil">Teknik Sipil</option>
                        <option value="Agribisnis">Agribisnis</option>
                        <option value="Bisnis Digital">Bisnis Digital</option>
                        <option value="Teknologi Pengolahan Hasil Ternak">Teknologi Pengolahan Hasil Ternak</option>
                    </select>
            </div>

            <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div> --}}

            <div class="d-flex justify-content-between">
                <a href="{{ route('akun_staf.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            
            </div>
        </form>
    </div>
</div>
@endsection