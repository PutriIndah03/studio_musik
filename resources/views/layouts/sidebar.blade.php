<div class="sidebar" id="sidebar">
    <div class="text-center mb-3">
        @php
        $user = auth()->user();
        $role = $user->role;
        $foto = null;

        if ($role === 'mahasiswa') {
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $user->username)->first();
            $foto = $mahasiswa && $mahasiswa->foto ? asset('path/to/mahasiswa/images/' . $mahasiswa->foto) : null;
        } elseif ($role === 'staf') {
            $staf = \App\Models\Staf::where('nim', $user->username)->first();
            $foto = $staf && $staf->foto ? asset('path/to/staf/images/' . $staf->foto) : null;
        } else {
            // Role 'pembina' menggunakan tabel users
            $foto = $user->image ? asset('path/to/user/images/' . $user->image) : null;
        }

        $initials = strtoupper(substr($user->nama, 0, 1)); // Inisial Nama
    @endphp
    
    <div class="profile-container">
        @if($foto)
            <img src="{{ $foto }}" class="profile-img" alt="Profile">
        @else
            <div class="profile-placeholder">{{ $initials }}</div>
        @endif
    </div>
    

        <h5>{{ ucfirst(auth()->user()->role) }}</h5>
    </div>
    
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-white" data-page="dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="peminjaman"><i class="bi bi-file-earmark-text me-2"></i> Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="pengembalian"><i class="bi bi-arrow-left-right me-2"></i> Pengembalian</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="jadwal"><i class="bi bi-calendar-check me-2"></i> Jadwal Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="riwayat"><i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman</a></li>
        <li class="nav-item"><a href="/studio_musik" class="nav-link text-white" data-page="studio"><i class="bi bi-music-note-beamed me-2"></i> Studio Musik</a></li>
        <li class="nav-item"><a href="/alat_musik" class="nav-link text-white" data-page="alat"><i class="bi bi-music-player me-2"></i> Alat Musik</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="validasi_peminjaman"><i class="bi bi-check-circle me-2"></i> Validasi Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="validasi_pengembalian"><i class="bi bi-x-circle me-2"></i> Validasi Pengembalian</a></li>
        <li class="nav-item"><a href="/akun_staf" class="nav-link text-white" data-page="akun_staf"><i class="bi bi-people me-2"></i> Akun Staf</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="laporan"><i class="bi bi-clipboard-data me-2"></i> Laporan</a></li>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let menuItems = document.querySelectorAll(".nav-link");
        let currentPath = window.location.pathname;

        // RESET MENU SAAT LOGIN BERHASIL
        if (sessionStorage.getItem("newLogin")) {
            localStorage.setItem("activeMenu", "/dashboard");
            sessionStorage.removeItem("newLogin"); // Hapus indikator login
        }

        let savedPath = localStorage.getItem("activeMenu") || "/dashboard";

        menuItems.forEach(item => {
            if (item.getAttribute("href") === savedPath) {
                item.classList.add("active", "bg-primary");
            }

            item.addEventListener("click", function () {
                menuItems.forEach(i => i.classList.remove("active", "bg-primary"));
                this.classList.add("active", "bg-primary");

                // Simpan menu yang diklik
                localStorage.setItem("activeMenu", this.getAttribute("href"));
            });
        });
    });
</script>
