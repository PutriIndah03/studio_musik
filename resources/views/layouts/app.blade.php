<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Musik Poliwangi</title>

    <!-- Bootstrap CSS -->
    <link rel="icon" href="{{ asset('img/logo.png') }}"" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@if (session('setActiveMenu'))
<script>
    localStorage.setItem("activeMenu", "{{ session('setActiveMenu') }}");
</script>
@endif
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
    window.userRole = "{{ Auth::user()->role }}";
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