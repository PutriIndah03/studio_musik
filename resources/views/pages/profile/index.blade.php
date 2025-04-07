@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <br>
    <h2 class="text-center fw-bold">Profile</h2>
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
        }, 5000); // Hilang dalam 5 detik
    </script>
@endif

    <div class="row">
        <!-- Profile Card -->
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 90%;">
            
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="d-flex justify-content-center">
                    <div class="text-center position-relative d-inline-block" style="cursor: pointer;">
                        <!-- Foto Profil atau Inisial -->
                        <label for="profileImageInput" class="position-relative d-inline-block">
                            @php
                                $foto = null;
                                if ($user->mahasiswa && $user->mahasiswa->foto) {
                                    $foto = asset('storage/' . $user->mahasiswa->foto);
                                } elseif ($user->staf && $user->staf->foto) {
                                    $foto = asset('storage/' . $user->staf->foto);
                                }
                            @endphp
                            
                            @if($foto)
                                <img id="profileImagePreview" src="{{ $foto }}" 
                                    class="rounded-circle border border-danger p-1" 
                                    width="120" height="120">
                            @else
                                <div id="profileImagePlaceholder" 
                                    class="rounded-circle border border-danger d-flex align-items-center justify-content-center"
                                    style="width: 120px; height: 120px; font-size: 48px; background-color: #f8d7da; color: #dc3545;">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                        </label>
                
                        <!-- Ikon Kamera -->
                        <div id="cameraIcon" class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow" 
                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-camera-fill text-danger" style="font-size: 18px;"></i>
                        </div>
                
                        <!-- Input File (Disembunyikan) -->
                        <input type="file" id="profileImageInput" name="foto" class="d-none" accept="image/*">
                    </div>
                </div>
                
                <script>
                document.getElementById('profileImageInput').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            let previewImage = document.getElementById('profileImagePreview');
                            let placeholder = document.getElementById('profileImagePlaceholder');
                            
                            if (previewImage) {
                                previewImage.src = e.target.result;
                            } else {
                                let newImage = document.createElement('img');
                                newImage.id = 'profileImagePreview';
                                newImage.src = e.target.result;
                                newImage.className = "rounded-circle border border-danger p-1";
                                newImage.width = 120;
                                newImage.height = 120;
                                
                                placeholder.replaceWith(newImage);
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
                
                // Klik pada gambar atau ikon kamera akan membuka input file
                document.getElementById('profileImagePreview')?.addEventListener('click', function() {
                    document.getElementById('profileImageInput').click();
                });
                document.getElementById('cameraIcon').addEventListener('click', function() {
                    document.getElementById('profileImageInput').click();
                });
                </script>
                
                <div class="row mt-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">NIM</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->nim ?? $user->staf->nim ?? '-' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" class="form-control" name="nama" value="{{ $user->nama }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->mahasiswa->email ?? $user->staf->email ?? '-' }}">
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">No HP</label>
                        <input type="text" class="form-control" name="no_hp" value="{{ $user->mahasiswa->no_hp ?? $user->staf->no_hp ?? '-' }}">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Program Studi</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->mahasiswa->prodi ?? $user->staf->prodi ?? '-' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select class="form-select" name="jenis_kelamin">
                            <option value="" {{ empty($user->mahasiswa->jenis_kelamin) && empty($user->staf->jenis_kelamin) ? 'selected' : '' }}>-</option>
                            <option value="Laki-laki" 
                                {{ ($user->mahasiswa->jenis_kelamin ?? $user->staf->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" 
                                {{ ($user->mahasiswa->jenis_kelamin ?? $user->staf->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                    </div>
                    
                    
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const profileImage = document.getElementById('profileImage');
                const profileInitial = document.getElementById('profileInitial');
    
                if (profileImage) {
                    profileImage.src = e.target.result;
                } else {
                    let imgTag = document.createElement('img');
                    imgTag.src = e.target.result;
                    imgTag.className = "rounded-circle border border-danger p-1";
                    imgTag.width = 120;
                    imgTag.height = 120;
                    imgTag.style.cursor = "pointer";
                    imgTag.onclick = function() {
                        document.getElementById('profileImageInput').click();
                    };
    
                    profileInitial.replaceWith(imgTag);
                }
            };
            reader.readAsDataURL(file);
        }
    }
    </script>
@endsection
