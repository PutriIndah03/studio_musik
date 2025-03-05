@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center">Formulir Ajukan Peminjaman Alat Musik</h2>
    <br>
    <div class="card shadow p-4">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control bg-light" value="{{ $alats->nama }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" min="1" max="{{ $alats->jumlah }}" value="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <input type="text" class="form-control bg-light" value="{{ $alats->kondisi }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal dan Waktu Pemakaian</label>
                <input type="datetime-local" class="form-control" name="tanggal_pinjam" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal dan Waktu Kembali</label>
                <input type="datetime-local" class="form-control" name="tanggal_kembali" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alasan</label>
                <textarea class="form-control" name="alasan" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Jaminan</label>
                <input type="text" class="form-control bg-light" name="jaminan" value="KTP" readonly>
            </div>
            <div class="d-flex justify-content-between">
                <a href="" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

            <!-- Input hidden untuk ID alat -->
            <input type="hidden" name="alat_id" value="{{ $alats->id }}">
        </form>
    </div>
</div>
@endsection
