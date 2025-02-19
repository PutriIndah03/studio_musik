@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h3 class="fw-bold text-center">Studio dan Alat Musik</h3>
    <br>
    <div class="row mt-3">
        @foreach ($studios as $studio)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <img src="{{ asset('storage/' . $studio->foto) }}" class="card-img-top img-fluid rounded border border-secondary p-1" alt="{{ $studio->nama }}" style="max-height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $studio->nama }}</h5>
                    <p class="card-text">
                        Status: 
                        @if ($studio->status === 'Tersedia')
                            <span class="text-success">{{ $studio->status }}</span>
                        @else
                            <span class="text-danger">{{ $studio->status }}</span>
                        @endif
                    </p>
                    <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                </div>
            </div>
        </div>
        @endforeach

        {{-- @foreach ($alatMusik as $alat)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card">
                <img src="{{ asset('storage/' . $alat->foto) }}" class="card-img-top img-fluid rounded border border-secondary p-1" alt="{{ $alat->nama }}" style="max-height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">{{ $alat->nama }}</h5>
                    <p class="card-text">Kondisi: {{ $alat->kondisi }} <br> Jumlah: {{ $alat->jumlah }}</p>
                    <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                </div>
            </div>
        </div>
        @endforeach --}}
    </div>
</div>
@endsection
