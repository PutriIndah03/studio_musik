<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Musik Poliwangi</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <!-- Sidebar -->
@include('layouts.sidebar')

    <!-- Konten utama -->
    <div class="content content-expanded" id="main-content">
        
        <!-- Navbar -->
@include('layouts.navbar')

        <!-- Konten halaman -->
        <div class="main-content-wrapper">
            <!-- Konten halaman -->
            @yield('content')
        </div>

        <!-- Footer -->
@include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script defer>
    document.addEventListener("DOMContentLoaded", function () {
        let sidebar = document.getElementById("sidebar");
        let mainContent = document.getElementById("main-content");
        let toggleSidebar = document.getElementById("toggleSidebar");

        toggleSidebar.addEventListener("click", function() {
            sidebar.classList.toggle("sidebar-hidden");
            if (sidebar.classList.contains("sidebar-hidden")) {
                mainContent.style.marginLeft = "0";
                mainContent.style.width = "100%";
            } else {
                mainContent.style.marginLeft = "250px";
                mainContent.style.width = "calc(100% - 250px)";
            }
        });
    });
</script>

</body>
</html>