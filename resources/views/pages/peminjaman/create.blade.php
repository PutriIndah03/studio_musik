@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center">Formulir Ajukan Peminjaman Alat Musik</h2>
    <br>
    <div class="card shadow p-4">
        <form action="{{ route('peminjaman.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Pilih Alat Musik</label>
                <div class="col-md-4 col-sm-6">
                    @foreach ($alats as $alat)
                        <div class="form-check">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="alat_id[]" 
                                value="{{ $alat->id }}" 
                                id="alat{{ $alat->id }}" 
                                @if($alat->kondisi === 'Rusak' || $alat->status === 'Tidak Tersedia') disabled @endif
                            >
                            <label class="form-check-label" for="alat{{ $alat->id }}">
                                {{ $alat->kode }} - {{ $alat->nama }} - {{ $alat->kondisi }}
                                @if($alat->kondisi === 'Rusak' || $alat->status === 'Tidak Tersedia')
                                    <span class="text-danger">(Tidak Tersedia)</span>
                                @endif
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
            
            {{-- <div class="mb-3">
                <label class="form-label">Kondisi</label>
                <input type="text" class="form-control bg-light" value="{{ $alats->kondisi }}" readonly>
            </div> --}}
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
                <a href="{{ route('dashboard.mahasiswa') }}" class="btn btn-danger">Kembali</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection
