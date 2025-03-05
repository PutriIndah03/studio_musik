@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h3 class="fw-bold text-center">Studio dan Alat Musik</h3>
    <br>

    <!-- Studio Musik Section -->
    <div class="row mt-3">
        @foreach ($studios as $studio)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('storage/' . $studio->foto) }}" 
                    class="card-img-top border border-secondary p-1"
                    alt="{{ $studio->nama }}" 
                    style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title">{{ $studio->nama }}</h5>
                    <p class="card-text" style="text-align: left">
                        Status: 
                        @if ($studio->status === 'Tersedia')
                            <span class="text-success fw-bold">{{ $studio->status }}</span>
                        @else
                            <span class="text-danger fw-bold">{{ $studio->status }}</span>
                        @endif
                    </p>
                    <div class="mt-auto">
                        <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Garis Pemisah -->
    <hr class="my-4 border-3">

    <!-- Alat Musik Section -->
    <div class="row mt-3">
        @foreach ($alats as $alat)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('storage/' . $alat->foto) }}" 
                    class="card-img-top border border-secondary p-1"
                    alt="{{ $alat->nama }}" 
                    style="height: 200px; object-fit: cover;">
                <div class="card-body d-flex flex-column text-center">
                    <h5 class="card-title">{{ $alat->nama }}</h5>
                    <p style="text-align: left">Kondisi: {{ $alat->kondisi }}</p>
                    <p style="text-align: left">Jumlah: {{ $alat->jumlah }}</p>
                    <p class="card-text" style="text-align: left">
                        Status: 
                        @if ($alat->status === 'Tersedia')
                            <span class="text-success fw-bold">{{ $alat->status }}</span>
                        @else
                            <span class="text-danger fw-bold">{{ $alat->status }}</span>
                        @endif
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('peminjaman.create', $alat->id) }}" class="btn btn-primary w-100">
                            Ajukan Peminjaman
                        </a>                        
                        
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
