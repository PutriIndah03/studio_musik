@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Akun Staf</h2>

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
        }, 5000); // Hilang dalam 5 detik
    </script>
@endif


    <a href="{{ route('akun_staf.create') }}" class="btn btn-success mb-3">Tambah Data</a>
    <table class="table table-bordered table-sm small">
        <thead>
            <tr>
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">NIM</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">Prodi</th>
                <th style="background-color: #0d6efd; color: white;">Email</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($akuns as $index => $akun)
            <tr>
                <td>{{ $loop->iteration + ($akuns->currentPage() - 1) * $akuns->perPage() }}</td>
                <td>{{ $akun->nim }}</td>
                <td>{{ $akun->nama }}</td>
                <td>{{ $akun->prodi }}</td>
                <td>{{ $akun->email }}</td>
                <td style="width: 200px; text-align: center;"> <!-- Lebar lebih besar untuk reset password -->
                    <div class="d-flex justify-content-center gap-1">
                        {{-- <a href="{{ route('akun_staf.edit', $akun->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a> --}}
                        <form action="{{ route('akun_staf.destroy', $akun->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                        <form action="{{ route('akun_staf.reset_password', $akun->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" onclick="return confirm('Yakin ingin mereset password akun ini?')">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $akuns->links() }}
    </div>
</div>
@endsection
