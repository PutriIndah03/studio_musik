@extends('layouts.app')

@section('content')
<br>
<div class="container mt-4">
    <h2 class="mb-4 text-center fw-bold">Alat Musik</h2>
    <a href="{{ route('alat_musik.create') }}" class="btn btn-success mb-3">Tambah Data</a>
    <table class="table table-bordered table-sm small">
        <thead>
            <tr>
                <th style="background-color: #0d6efd; color: white;">No</th>
                <th style="background-color: #0d6efd; color: white;">Kode</th>
                <th style="background-color: #0d6efd; color: white;">Nama</th>
                {{-- <th style="background-color: #0d6efd; color: white;">Tipe</th> --}}
                <th style="background-color: #0d6efd; color: white;">Gambar</th>
                {{-- <th style="background-color: #0d6efd; color: white;">Jumlah</th> --}}
                <th style="background-color: #0d6efd; color: white;">Kondisi</th>
                <th style="background-color: #0d6efd; color: white;">Status</th>
                <th style="background-color: #0d6efd; color: white;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alats as $index => $alat)
            <tr>
                <td>{{ $loop->iteration + ($alats->currentPage() - 1) * $alats->perPage() }}</td>
                <td>{{ $alat->kode }}</td>
                <td>{{ $alat->nama }}</td>
                {{-- <td>{{ $alat->tipe }}</td> --}}
                <td class="foto">
                    @if($alat->foto)
                        <img src="{{ asset('storage/' . $alat->foto) }}" width="100" height="60" alt="Studio Musik">
                    @else
                        <img src="" alt="No Image">
                    @endif
                </td>
                {{-- <td>{{ $alat->jumlah }}</td> --}}
                <td>{{ $alat->kondisi }}</td>
                <td>
                    <span class="badge {{ $alat->status == 'Tersedia' ? 'bg-success' : 'bg-danger'}}">
                        {{ $alat->status }}
                    </span>
                </td>
                <td style="width: 150px; text-align: center;"> <!-- Menyesuaikan lebar kolom & tengah -->
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('alat_musik.edit', $alat->id) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <form action="{{ route('alat_musik.destroy', $alat->id) }}" method="POST">
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
    <div class="d-flex justify-content-center">
        {{ $alats->links() }}
    </div>
</div>
@endsection
