@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Studio Musik</h2>
    <a href="{{ route('studio_musik.create') }}" class="btn btn-success mb-3">Tambah Data</a>
    <table class="table table-bordered small">
        <thead>
            <tr>
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                <th style="background-color: #0d6efd; color: white;">Gambar</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($studios as $index => $studio)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $studio->nama }}</td>
                <td class="foto">
                    @if($studio->foto)
                        <img src="{{ asset('storage/' . $studio->foto) }}" width="100" height="60" alt="Studio Musik">
                    @else
                        <img src="" alt="No Image">
                    @endif
                </td>
                
                <td>
                    <span class="badge {{ $studio->status == 'Tersedia' ? 'bg-success' : 'bg-danger' }}">
                        {{ $studio->status }}
                    </span>
                </td>
                <td style="width: 150px; text-align: center;"> <!-- Menyesuaikan lebar kolom & tengah -->
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('studio_musik.edit', $studio->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('studio_musik.destroy', $studio->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
