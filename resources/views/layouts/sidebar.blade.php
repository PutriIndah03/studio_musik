<div class="sidebar" id="sidebar" style="max-height: 100vh; overflow-y: auto;">
    <div class="text-center mb-3">
        @php
        $user = auth()->user();
        $role = ucfirst($user->role);
        $foto = null;

        if ($user->role === 'mahasiswa') {
            $mahasiswa = \App\Models\Mahasiswa::where('nim', $user->username)->first();
            $foto = $mahasiswa && $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : null;
        } elseif ($user->role === 'staf') {
            $staf = \App\Models\Staf::where('nim', $user->username)->first();
            $foto = $staf && $staf->foto ? asset('storage/' . $staf->foto) : null;
        } else {
            $foto = $user->image ? asset('storage/' . $user->image) : null;
        }

        $initials = strtoupper(substr($user->nama, 0, 1)); // Inisial Nama
        @endphp

        <!-- Logo -->
        <div class="logo-container mb-2">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
            <img src="{{ asset('img/logo2.png') }}" alt="Logo" class="logo-img">
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
        @if(auth()->user()->role === 'pembina' || auth()->user()->role === 'staf')
        <li class="nav-item">
            <a href="/dashboard" class="nav-link text-white" data-page="dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'pembina')
        <li class="nav-item">
            <a href="/akun_staf" class="nav-link text-white" data-page="akun_staf">
                <i class="bi bi-people me-2"></i> Akun Staf
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'mahasiswa')
        <li class="nav-item">
            <a href="/dashboard/mahasiswa" class="nav-link text-white" data-page="dashboard">
                <i class="bi bi-house-door-fill me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/peminjaman" class="nav-link text-white" data-page="peminjaman">
                <i class="bi bi-file-earmark-text me-2"></i> Peminjaman
            </a>
        </li>
        <li class="nav-item">
            <a href="/pengembalian" class="nav-link text-white" data-page="pengembalian">
                <i class="bi bi-box-arrow-right me-2"></i> Pengembalian
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'staf')
        <!-- Dropdown Menu for Data -->
                    <li class="nav-item">
                        <a href="/studio_musik" class="nav-link text-white" data-page="studio">
                            <i class="bi bi-music-note-beamed me-2"></i> Studio Musik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/alat_musik" class="nav-link text-white" data-page="alat">
                            <i class="bi bi-music-player me-2"></i> Alat Musik
                        </a>
                    </li>

        <li class="nav-item">
            <a href="/validasipeminjaman" class="nav-link text-white" data-page="validasi_peminjaman">
                <i class="bi bi-check-circle me-2"></i> Validasi Peminjaman
            </a>
        </li>
        <li class="nav-item">
            <a href="/validasipengembalian" class="nav-link text-white" data-page="validasi_pengembalian">
                <i class="bi bi-x-circle me-2"></i> Validasi Pengembalian
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a href="/jadwalPeminjaman" class="nav-link text-white" data-page="jadwal">
                <i class="bi bi-calendar-check me-2"></i> Jadwal Peminjaman
            </a>
        </li>

        @if(auth()->user()->role === 'mahasiswa')
        <li class="nav-item">
            <a href="/riwayatPeminjamanMhs" class="nav-link text-white" data-page="riwayat">
                <i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'pembina' || auth()->user()->role === 'staf')
        <li class="nav-item">
            <a href="/riwayatPeminjaman" class="nav-link text-white" data-page="riwayat">
                <i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman
            </a>
        </li>
        @endif

        @if(auth()->user()->role === 'pembina')
        <li class="nav-item">
            <a href="/laporan" class="nav-link text-white" data-page="laporan">
                <i class="bi bi-clipboard-data me-2"></i> Laporan
            </a>
        </li>
        @endif
    </ul>
    
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Ambil role dari blade (pastikan di blade ada definisi window.userRole)
    let role = window.userRole || "{{ auth()->user()->role ?? 'mahasiswa' }}";

    // Tentukan default path berdasarkan role
    let defaultPath = "/";
    if (role === "mahasiswa") {
        defaultPath = "/dashboard/mahasiswa";
    } else if (role === "staf" || role === "pembina") {
        defaultPath = "/dashboard/staf";
    }

    // Ambil role dan activeMenu dari localStorage
    let savedRole = localStorage.getItem("activeRole");
    let savedMenu = localStorage.getItem("activeMenu");

    // Jika role berubah, reset activeMenu ke defaultPath
    if (savedRole !== role) {
        localStorage.setItem("activeRole", role);
        localStorage.setItem("activeMenu", defaultPath);
        savedMenu = defaultPath;
    }

    let currentPath = window.location.pathname;
    let activeMenu = savedMenu || currentPath || defaultPath;

    let menuItems = document.querySelectorAll(".nav-link");

    // Fungsi untuk hapus semua aktif
    function clearActive() {
        menuItems.forEach(i => i.classList.remove("active", "bg-primary"));
    }

    // Set aktif sesuai href yang cocok dengan activeMenu atau currentPath
    let found = false;
    menuItems.forEach(item => {
        let href = item.getAttribute("href");

        // Cocokkan dengan activeMenu
        if (href === activeMenu) {
            clearActive();
            item.classList.add("active", "bg-primary");
            found = true;
        }
    });

    // Jika tidak ada yang cocok, coba cocokkan dengan currentPath langsung
    if (!found) {
        menuItems.forEach(item => {
            let href = item.getAttribute("href");
            if (href === currentPath) {
                clearActive();
                item.classList.add("active", "bg-primary");
                found = true;
            }
        });
    }

    // Jika masih tidak ada yang cocok, aktifkan defaultPath (biasanya dashboard)
    if (!found) {
        menuItems.forEach(item => {
            let href = item.getAttribute("href");
            if (href === defaultPath) {
                clearActive();
                item.classList.add("active", "bg-primary");
            }
        });
    }

    // Pasang event listener klik
    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            clearActive();
            this.classList.add("active", "bg-primary");

            // Simpan ke localStorage
            localStorage.setItem("activeMenu", this.getAttribute("href"));
            localStorage.setItem("activeRole", role);
        });
    });

    // Kalau ingin update highlight saat navigasi browser (back/forward)
    window.addEventListener("popstate", function () {
        let path = window.location.pathname;
        localStorage.setItem("activeMenu", path);

        clearActive();
        menuItems.forEach(item => {
            if (item.getAttribute("href") === path) {
                item.classList.add("active", "bg-primary");
            }
        });
    });
});

</script>



