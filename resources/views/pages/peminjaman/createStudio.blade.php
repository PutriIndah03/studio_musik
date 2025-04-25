
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Formulir Ajukan Peminjaman Studio Musik</h2>
    <div class="card p-4">
        <form action="{{ route('peminjaman.store2') }}" method="POST">
            @csrf

            <!-- Nama Studio -->
            <div class="mb-3">
                <label class="form-label fw-bold">Nama Studio</label>
                <select name="studio_id" class="form-select" required>
                    @if (!empty($studios) && $studios->count() > 0)
                        @foreach ($studios as $studio)
                            <option value="{{ $studio->id }}">{{ $studio->nama }}</option>
                        @endforeach
                    @else
                        <option value="">Studio tidak tersedia</option>
                    @endif
                </select>
            </div>

            <!-- Kategori Alat Musik -->
            <div class="mb-3">
                <label class="form-label fw-bold">Kategori</label>
                <select class="form-select" id="kategori-filter">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Tradisional">Tradisional</option>
                    <option value="Modern">Modern</option>
                </select>
            </div>

            <!-- Alat Musik -->
            <div class="mb-3">
                <label class="form-label fw-bold">Pilih Alat Musik</label>
                <div class="col-md-4 col-sm-6" id="alat-container">
                    @foreach ($alats as $alat)
                        <div 
                            class="form-check alat-item" 
                            data-kategori="{{ $alat->kategori }}">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="alat_id[]" 
                                value="{{ $alat->id }}" 
                                id="alat{{ $alat->id }}"
                                @if($alat->kondisi === 'Rusak' || $alat->status === 'Tidak Tersedia') disabled @endif
                            >
                            <label class="form-check-label" for="alat{{ $alat->id }}">
                                {{ $alat->nama }} - {{ $alat->kondisi }}
                                @if($alat->kondisi === 'Rusak' || $alat->status === 'Tidak Tersedia')
                                    <span class="text-danger">(Tidak Tersedia)</span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('alat_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tanggal & Waktu -->
            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal dan Waktu Pemakaian</label>
                <input type="datetime-local" name="tanggal_pinjam" class="form-control" required>
                @error('tanggal_pinjam')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Tanggal dan Waktu Kembali</label>
                <input type="datetime-local" name="tanggal_kembali" class="form-control" required>
                @error('tanggal_kembali')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Alasan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Penggunaan</label>
                <textarea class="form-control" name="alasan" rows="3" required></textarea>
            </div>

            <!-- Jaminan -->
            <div class="mb-3">
                <label class="form-label fw-bold">Jaminan</label>
                <input type="text" class="form-control bg-light" name="jaminan" value="KTM" readonly>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.mahasiswa') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

{{-- Script untuk filter kategori --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const kategoriSelect = document.getElementById('kategori-filter');
        const alatItems = document.querySelectorAll('.alat-item');

        // Sembunyikan semua alat saat halaman pertama kali dimuat
        alatItems.forEach(item => {
            item.style.display = 'none';
        });

        kategoriSelect.addEventListener('change', function () {
            const selectedKategori = this.value;

            alatItems.forEach(item => {
                const itemKategori = item.getAttribute('data-kategori');

                if (selectedKategori === "" || itemKategori === selectedKategori) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection

