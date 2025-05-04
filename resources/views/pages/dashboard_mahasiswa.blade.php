@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="fw-bold text-center">Studio dan Alat Musik</h2>
    <br>
    @if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        setTimeout(function () {
            let alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 500);
            }
        }, 5000);
    </script>
    @endif

    @php
        $isDisabled = $studios->isEmpty() && $alats->isEmpty();
    @endphp

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

    <!-- Alat Musik Section -->
    @if ($alats->isNotEmpty())
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
                    <p class="card-text text-start">
                        Kondisi:
                        @if ($alat->kondisi === 'Baik')
                            <span class="text-success fw-bold">{{ $alat->kondisi }}</span>
                        @elseif ($alat->kondisi === 'Rusak Ringan')
                            <span class="text-warning fw-bold">{{ $alat->kondisi }}</span>
                        @else
                            <span class="text-danger fw-bold">{{ $alat->kondisi }}</span>
                        @endif
                    </p>
                    <p class="text-start">Kode: {{ $alat->kode }}</p>
                    <p class="card-text text-start">
                        Status: 
                        @if ($alat->status === 'Tersedia')
                            <span class="text-success fw-bold">{{ $alat->status }}</span>
                        @else
                            <span class="text-danger fw-bold">{{ $alat->status }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $alats->appends(request()->except('page'))->links() }}
    </div>
    @endif

    <!-- Garis Pemisah -->
    @if ($studios->isNotEmpty() && $alats->isNotEmpty())
    <hr class="my-4 border-3">
    @endif

    <!-- Studio Musik Section -->
    @if ($studios->isNotEmpty())
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
                    <p class="card-text text-start">
                        Status: 
                        @if ($studio->status === 'Tersedia')
                            <span class="text-success fw-bold">{{ $studio->status }}</span>
                        @else
                            <span class="text-danger fw-bold">{{ $studio->status }}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center">
        {{ $alats->appends(request()->except('page'))->links() }}
    </div>
    @endif

</div>
@endsection
