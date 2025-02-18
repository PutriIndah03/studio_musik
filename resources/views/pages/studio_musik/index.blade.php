@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Studio Musik</h2>
    <a href="{{ route('studio_musik.create') }}" class="btn btn-success mb-3">Tambah Data</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">Gambar</th>
                <th style="background-color: #0d6efd; color: white;">Deskripsi</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Studio Musik</td>
                <td class="gambar"><img src="https://via.placeholder.com/100x60" alt="Studio Musik"></td>
                 <td>blablabla</td>
                  <td><span class="badge bg-success">Tersedia</span></td>
                <td class="aksi-btn">
                    <button class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i></button>
                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash-fill"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

@endsection
