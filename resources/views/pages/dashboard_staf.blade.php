@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-center">
    <div class="w-100">
        <h2 class="fw-bold text-center">Dashboard</h2>
        <div class="row mt-4 justify-content-center w-100">
            <div class="col-xl-10 col-lg-12 col-md-12">
                <div class="card bg-primary text-white p-4">
                    <h3 class="text-start">{{ $totalAlat }}</h3>
                    <p class="text-start">Rekap Alat</p>
                </div>
            </div>
            <div class="col-xl-10 col-lg-12 col-md-12 mt-3">
                <div class="card bg-success text-white p-4">
                    <h3 class="text-start">{{ $alatDipinjam }}</h3>
                    <p class="text-start">Rekap Alat yang dipinjam</p>
                </div>
            </div>
            <div class="col-xl-10 col-lg-12 col-md-12 mt-3">
                <div class="card bg-danger text-white p-4">
                    <h3 class="text-start">{{ $alatRusak }}</h3>
                    <p class="text-start">Rekap Alat yang rusak</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
