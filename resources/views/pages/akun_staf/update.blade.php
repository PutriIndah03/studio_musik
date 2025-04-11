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
                    <select name="prodi" class="form-select" required>
                        <option value="" disabled {{ old('prodi', $user->prodi) == '' ? 'selected' : '' }}>Pilih Program Studi</option>
                        <option value="Teknologi Rekayasa Perangkat Lunak" {{ old('prodi', $user->prodi) == 'Teknologi Rekayasa Perangkat Lunak' ? 'selected' : '' }}>Teknologi Rekayasa Perangkat Lunak</option>
                        <option value="Manajemen Bisnis Pariwisata" {{ old('prodi', $user->prodi) == 'Manajemen Bisnis Pariwisata' ? 'selected' : '' }}>Manajemen Bisnis Pariwisata</option>
                        <option value="Teknik Mesin" {{ old('prodi', $user->prodi) == 'Teknik Mesin' ? 'selected' : '' }}>Teknik Mesin</option>
                        <option value="Teknologi Rekayasa Komputer" {{ old('prodi', $user->prodi) == 'Teknologi Rekayasa Komputer' ? 'selected' : '' }}>Teknologi Rekayasa Komputer</option>
                        <option value="Teknik Sipil" {{ old('prodi', $user->prodi) == 'Teknik Sipil' ? 'selected' : '' }}>Teknik Sipil</option>
                        <option value="Agribisnis" {{ old('prodi', $user->prodi) == 'Agribisnis' ? 'selected' : '' }}>Agribisnis</option>
                        <option value="Bisnis Digital" {{ old('prodi', $user->prodi) == 'Bisnis Digital' ? 'selected' : '' }}>Bisnis Digital</option>
                        <option value="Teknologi Pengolahan Hasil Ternak" {{ old('prodi', $user->prodi) == 'Teknologi Pengolahan Hasil Ternak' ? 'selected' : '' }}>Teknologi Pengolahan Hasil Ternak</option>
                    </select>
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
