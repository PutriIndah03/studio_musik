<div class="sidebar" id="sidebar" style="max-height: 100vh; overflow-y: auto;">
    <div class="text-center mb-3">
        @php
        $user = auth()->user();
        $role = ucfirst($user->role);
        $foto = null;

        if ($user->role === 'mahasiswa') {
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $user->username)->first();
            $foto = $mahasiswa && $mahasiswa->foto ? asset('path/to/mahasiswa/images/' . $mahasiswa->foto) : null;
        } elseif ($user->role === 'staf') {
            $staf = \App\Models\Staf::where('nim', $user->username)->first();
            $foto = $staf && $staf->foto ? asset('path/to/staf/images/' . $staf->foto) : null;
        } else {
            $foto = $user->image ? asset('path/to/user/images/' . $user->image) : null;
        }

        $initials = strtoupper(substr($user->nama, 0, 1)); // Inisial Nama
        @endphp

        <!-- Logo -->
        <div class="logo-container mb-2">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
        </div>

        <hr class="my-2 border-2">
        <!-- Profil Container -->
        <div class="d-flex align-items-center justify-content-start profile-box">
            <!-- Foto Profil -->
            @if($foto)
                <img src="{{ $foto }}" class="profile-img me-2" alt="Profile">
            @else
                <div class="profile-placeholder me-2">{{ $initials }}</div>
            @endif

            <!-- Nama & Peran -->
            <div class="text-start">
                <h6 class="mb-0 fw-bold text-white">{{ $user->nama }}</h6>
                <p class="text-white mb-0">{{ $role }}</p>
            </div>
        </div>
        <hr class="my-2 border-2">
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-white" data-page="dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/dashboard/mahasiswa" class="nav-link text-white" data-page="dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item"><a href="/peminjaman" class="nav-link text-white" data-page="peminjaman"><i class="bi bi-file-earmark-text me-2"></i> Peminjaman</a></li>
        <li class="nav-item"><a href="/pengembalian" class="nav-link text-white" data-page="pengembalian"><i class="bi bi-box-arrow-right me-2"></i> Pengembalian</a></li>
        <li class="nav-item"><a href="/jadwalPeminjaman" class="nav-link text-white" data-page="jadwal"><i class="bi bi-calendar-check me-2"></i> Jadwal Peminjaman</a></li>
        <li class="nav-item"><a href="/riwayatPeminjamanMhs" class="nav-link text-white" data-page="riwayat"><i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman</a></li>
        <li class="nav-item"><a href="/riwayatPeminjaman" class="nav-link text-white" data-page="riwayat"><i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman</a></li>
        <li class="nav-item"><a href="/studio_musik" class="nav-link text-white" data-page="studio"><i class="bi bi-music-note-beamed me-2"></i> Studio Musik</a></li>
        <li class="nav-item"><a href="/alat_musik" class="nav-link text-white" data-page="alat"><i class="bi bi-music-player me-2"></i> Alat Musik</a></li>
        <li class="nav-item"><a href="/validasipeminjaman" class="nav-link text-white" data-page="validasi_peminjaman"><i class="bi bi-check-circle me-2"></i> Validasi Peminjaman</a></li>
        <li class="nav-item"><a href="/validasipengembalian" class="nav-link text-white" data-page="validasi_pengembalian"><i class="bi bi-x-circle me-2"></i> Validasi Pengembalian</a></li>
        <li class="nav-item"><a href="/akun_staf" class="nav-link text-white" data-page="akun_staf"><i class="bi bi-people me-2"></i> Akun Staf</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="laporan"><i class="bi bi-clipboard-data me-2"></i> Laporan</a></li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let menuItems = document.querySelectorAll(".nav-link");
        let currentPath = window.location.pathname;
        
        // Simpan route terakhir yang dikunjungi agar tetap aktif setelah reload
        localStorage.setItem("activeMenu", currentPath);
    
        menuItems.forEach(item => {
            if (item.getAttribute("href") === currentPath) {
                item.classList.add("active", "bg-primary");
            }
    
            item.addEventListener("click", function () {
                menuItems.forEach(i => i.classList.remove("active", "bg-primary"));
                this.classList.add("active", "bg-primary");
    
                // Simpan menu yang diklik ke localStorage
                localStorage.setItem("activeMenu", this.getAttribute("href"));
            });
        });
    
        // Pastikan menu berubah ketika halaman berubah tanpa perlu refresh
        window.addEventListener("popstate", function () {
            let updatedPath = window.location.pathname;
            localStorage.setItem("activeMenu", updatedPath);
            menuItems.forEach(item => {
                item.classList.remove("active", "bg-primary");
                if (item.getAttribute("href") === updatedPath) {
                    item.classList.add("active", "bg-primary");
                }
            });
        });
    });
    </script>
    
