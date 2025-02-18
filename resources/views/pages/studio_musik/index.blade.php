@extends('layouts.app')

@section('content')
    <div class="content">
        <h2>Studio Musik</h2>
        <button class="btn-add">Tambah Data</button>
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Gambar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Studio Musik</td>
                <td><img src="studio.jpg" alt="Studio Musik" width="100"></td>
                <td>Tersedia</td>
                <td class="actions">
                    <button class="edit-btn"><i class="fas fa-edit"></i></button>
                    <button class="delete-btn"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        </table>
    </div>
      @endsection