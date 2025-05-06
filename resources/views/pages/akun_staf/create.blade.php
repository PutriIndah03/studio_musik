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
                <option value="Bisnis Digital">Bisnis Digital</option>
                <option value="Teknologi Rekayasa Komputer">Teknologi Rekayasa Komputer</option>
                <option value="Teknik Sipil">Teknik Sipil</option>
                <option value="Teknologi Rekayasa Kontruksi Jalan & Jembatan">Teknologi Rekayasa Kontruksi Jalan & Jembatan</option>
                <option value="Teknologi Rekayasa Kontruksi Bangunan Gedung">Teknologi Rekayasa Kontruksi Bangunan Gedung</option>
                <option value="Manajemen Kontruksi">Manajemen Kontruksi</option>
                <option value="Teknologi Rekayasa Manufaktur">Teknologi Rekayasa Manufaktur</option>
                <option value="Teknik Menufaktur Kapal">Teknik Menufaktur Kapal</option>
                <option value="Agribisnis">Agribisnis</option>
                <option value="Teknologi Pengolahan Hasil Ternak">Teknologi Pengolahan Hasil Ternak</option>
                <option value="Pengembangan Produk Agroindustri">Pengembangan Produk Agroindustri</option>
                <option value="Teknologi Budi Daya Perikanan / Teknologi Akuakultur">Teknologi Budi Daya Perikanan / Teknologi Akuakultur</option>
                <option value="Teknologi Produksi Tanaman Pangan">Teknologi Produksi Tanaman Pangan</option>
                <option value="Teknologi Produksi Ternak">Teknologi Produksi Ternak</option>
                <option value="Manajemen Bisnis Pariwisata">Manajemen Bisnis Pariwisata</option>
                <option value="Destinasi Pariwisata">Destinasi Pariwisata</option>
                <option value="Pengelolaan Perhotelan">Pengelolaan Perhotelan</option>
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