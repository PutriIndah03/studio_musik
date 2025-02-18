<div class="sidebar sidebar-hidden" id="sidebar">
    <div class="text-center mb-3">
        <img src="profile.jpg" class="rounded-circle" width="80" height="80" alt="Profile">
        <h5 class="mt-2">John Doe</h5>
        <small>Mahasiswa</small>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="/dashboard" class="nav-link text-white" data-page="dashboard"><i class="bi bi-house-door-fill me-2"></i> Dashboard</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="peminjaman"><i class="bi bi-file-earmark-text me-2"></i> Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="pengembalian"><i class="bi bi-arrow-left-right me-2"></i> Pengembalian</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="jadwal"><i class="bi bi-calendar-check me-2"></i> Jadwal Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="riwayat"><i class="bi bi-clock-history me-2"></i> Riwayat Peminjaman</a></li>
        <li class="nav-item"><a href="/studio_musik" class="nav-link text-white" data-page="studio"><i class="bi bi-music-note-beamed me-2"></i> Studio Musik</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="alat"><i class="bi bi-music-player me-2"></i> Alat Musik</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="validasi_peminjaman"><i class="bi bi-check-circle me-2"></i> Validasi Peminjaman</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="validasi_pengembalian"><i class="bi bi-x-circle me-2"></i> Validasi Pengembalian</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="akun_staf"><i class="bi bi-people me-2"></i> Akun Staf</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-page="laporan"><i class="bi bi-clipboard-data me-2"></i> Laporan</a></li>
    </ul>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let menuItems = document.querySelectorAll(".nav-link");
            let currentPage = localStorage.getItem("activePage");
            
            menuItems.forEach(item => {
                if (item.getAttribute("data-page") === currentPage) {
                    item.classList.add("active", "bg-primary");
                }
                
                item.addEventListener("click", function() {
                    menuItems.forEach(i => i.classList.remove("active", "bg-primary"));
                    this.classList.add("active", "bg-primary");
                    localStorage.setItem("activePage", this.getAttribute("data-page"));
                });
            });
        });
    </script>
</div>
