@extends('layouts.app')

@section('content')
<br>
        <div class="container mt-4">
            <h3 class="fw-bold text-center">Studio dan Alat Musik</h3>
            <br>
            <div class="row mt-3">
                <div class="col-md-4">
                    <div class="card">
                        <img src="studio-musik.jpg" class="card-img-top" alt="Studio Musik">
                        <div class="card-body">
                            <h5 class="card-title">Studio Musik</h5>
                            <p class="card-text">Status: Tersedia</p>
                            <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="gitar.jpg" class="card-img-top" alt="Gitar">
                        <div class="card-body">
                            <h5 class="card-title">Gitar</h5>
                            <p class="card-text">Kondisi: Baik <br> Jumlah: 3</p>
                            <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="seruling.jpg" class="card-img-top" alt="Seruling">
                        <div class="card-body">
                            <h5 class="card-title">Seruling</h5>
                            <p class="card-text">Kondisi: Rusak <br> Jumlah: 2</p>
                            <button class="btn btn-primary w-100">Ajukan Peminjaman</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection