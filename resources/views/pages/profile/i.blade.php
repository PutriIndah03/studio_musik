@extends('layouts.app')

@section('content') 
<div class="container-fluid"> <br> 
    <h3 class="text-center fw-bold">Profile</h3> <br>
    <div class="row"> 
        <!-- Profile Card -->
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 90%;">
            
            <div class="text-center">
                <img src="{{ asset($user->image ? 'uploads/profile/' . $user->image : 'uploads/profile/default.png') }}" 
                    class="rounded-circle border border-danger p-1" width="120" height="120">
            </div>

            <form class="mt-3">
                <!-- Jika User adalah Mahasiswa -->
                @if($user->mahasiswa)
                    <div class="mb-2">
                        <label class="form-label">NIM</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->nim }}" readonly>
                    </div>
                    
                    <div class="mb-2">
                        <label class="form-label">Program Studi</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->prodi }}" readonly>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $user->mahasiswa->email }}">
                    </div>
    
                    <div class="mb-2">
                        <label class="form-label">No HP</label>
                        <input type="text" class="form-control" value="{{ $user->mahasiswa->no_hp }}">
                    </div>
    
                    <div class="mb-2">
                        <label class="form-label">Jenis Kelamin</label>
                        <input type="text" class="form-control" value="{{ $user->mahasiswa->jenis_kelamin }}">
                    </div>
                
                <!-- Jika User adalah Staf -->
                @elseif($user->staf)
                <div class="mb-2">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->nim }}" readonly>
                </div>
                
                <div class="mb-2">
                    <label class="form-label">Program Studi</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->prodi }}" readonly>
                </div>

                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" value="{{ $user->mahasiswa->email }}">
                </div>

                <div class="mb-2">
                    <label class="form-label">No HP</label>
                    <input type="text" class="form-control" value="{{ $user->mahasiswa->no_hp }}">
                </div>

                <div class="mb-2">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" class="form-control" value="{{ $user->mahasiswa->jenis_kelamin }}">
                </div>
                @endif

                <div class="mb-2">
                    <label class="form-label">Nama</label>
                    <input type="text" class="form-control" value="{{ $user->nama }}">
                </div>

                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
