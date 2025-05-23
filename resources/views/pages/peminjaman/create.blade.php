@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center fw-bold">Formulir Ajukan Peminjaman Alat Musik</h2>
    <br>
    <div class="card shadow p-4">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf

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

            <div class="mb-3">
                <label class="form-label fw-bold">Catatan Penggunaan</label>
                <textarea class="form-control" name="alasan" rows="3" placeholder="Tuliskan alasan peminjaman..." required></textarea>
                @error('alasan')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Jaminan</label>
                <input type="text" class="form-control bg-light" name="jaminan" value="KTP" readonly>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('dashboard.mahasiswa') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

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
