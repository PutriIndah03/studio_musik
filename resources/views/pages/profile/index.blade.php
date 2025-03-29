@extends('layouts.app')

@section('content') 
<div class="container-fluid">
    <br>
    <h2 class="text-center fw-bold">Profile</h2>
    <br>
    <div class="row">
        <!-- Profile Card -->
        <div class="card shadow-sm p-4 mx-auto" style="max-width: 90%;">
            
            <form method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="d-flex justify-content-center">
                    <div class="text-center position-relative d-inline-block" style="cursor: pointer;">
                        <!-- Foto Profil atau Inisial -->
                        <label for="profileImageInput" class="position-relative d-inline-block">
                            @if($user->image)
                                <img id="profileImagePreview" src="{{ asset('storage/profile/' . $user->image) }}" 
                                    class="rounded-circle border border-danger p-1" width="120" height="120">
                            @else
                                <div id="profileImagePreview" class="rounded-circle border border-danger d-flex align-items-center justify-content-center"
                                    style="width: 120px; height: 120px; font-size: 48px; background-color: #f8d7da; color: #dc3545;">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </div>
                            @endif
                        </label>
                
                        <!-- Ikon Kamera -->
                        <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow" 
                            style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-camera-fill text-danger" style="font-size: 18px;"></i>
                        </div>
                
                        <!-- Input File (Disembunyikan) -->
                        <input type="file" id="profileImageInput" name="image" class="d-none" accept="image/*">
                    </div>
                </div>
                
                
                <script>
                document.getElementById('profileImagePreview').addEventListener('click', function() {
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
                        <input type="text" class="form-control" name="jenis_kelamin" value="{{ $user->mahasiswa->jenis_kelamin ?? $user->staf->jenis_kelamin ?? '-' }}">
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
