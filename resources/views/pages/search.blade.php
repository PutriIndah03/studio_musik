@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center">Studio dan Alat Musik</h2>
    <br>

    {{-- <h5 class="text-center">Hasil Pencarian untuk: <strong>{{ $query }}</strong></h5>
    <br> --}}

    @php
        $isDisabled = $studios->isEmpty() && $alats->isEmpty();
    @endphp

    <!-- Tombol Ajukan Peminjaman -->
    <div class="d-flex justify-content-center mb-3">
        <div class="dropdown">
            <button class="btn btn-primary d-flex align-items-center gap-2 px-3 py-2" type="button" id="peminjamanDropdown" data-bs-toggle="dropdown" aria-expanded="false" {{ $isDisabled ? 'disabled' : '' }}>
                Ajukan Peminjaman
            </button>
            @if (!$isDisabled)
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="peminjamanDropdown">
                    @if ($studios->isNotEmpty())
                        <a class="dropdown-item" href="{{ route('peminjaman.createStudio', ['studio_musik' => $studios->first()->id]) }}">
                            Studio Musik
                        </a>
                    @endif
                    @if ($alats->isNotEmpty())
                        <li><a class="dropdown-item" href="{{ route('peminjaman.create') }}">Alat Musik</a></li>
                    @endif
                </ul>
            @endif
        </div>
    </div>

    @if ($studios->isEmpty() && $alats->isEmpty())
        <p class="text-center mt-4">Tidak ada alat musik atau studio musik ditemukan.</p>
    @else
        <!-- Alat Musik -->
<!-- Alat Musik -->
@if ($alats->isNotEmpty())
<div class="row mt-3">
    @foreach ($alats as $alat)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('storage/' . $alat->foto) }}" class="card-img-top border border-secondary p-1" alt="{{ $alat->nama }}" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $alat->nama }}</h5>
                    <p class="text-start">Kategori: {{ $alat->kategori}}</p>
                    <p class="text-start">Kondisi: 
                        <span class="fw-bold {{ $alat->kondisi === 'Baik' ? 'text-success' : ($alat->kondisi === 'Rusak Ringan' ? 'text-warning' : 'text-danger') }}">
                            {{ $alat->kondisi }}
                        </span>
                    </p>
                    <p class="text-start">Status: 
                        <span class="fw-bold {{ $alat->status === 'Tersedia' ? 'text-success' : 'text-danger' }}">
                            {{ $alat->status }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $alats->links() }}
</div>
@endif


@if ($studios->isNotEmpty())
<hr class="my-4 border-3">

<div class="row mt-3">
    @foreach ($studios as $studio)
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ asset('storage/' . $studio->foto) }}" class="card-img-top border border-secondary p-1" alt="{{ $studio->nama }}" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ $studio->nama }}</h5>
                    <p class="text-start">Status: 
                        <span class="fw-bold {{ $studio->status === 'Tersedia' ? 'text-success' : 'text-danger' }}">
                            {{ $studio->status }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="d-flex justify-content-center">
    {{ $studios->links() }}
</div>
@endif

    @endif
</div>
@endsection
