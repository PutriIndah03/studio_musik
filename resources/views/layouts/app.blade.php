<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Musik Poliwangi</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
@vite(['resources/css/app.css'])
</head>
<body>

    <!-- Sidebar -->
@include('layouts.sidebar')

    <!-- Konten utama -->
    <div class="content content-expanded" id="main-content">
        
        <!-- Navbar -->
@include('layouts.navbar')

        <!-- Konten halaman -->
@yield('content')

        <!-- Footer -->
@include('layouts.footer')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("toggleSidebar").addEventListener("click", function() {
            document.getElementById("sidebar").classList.toggle("sidebar-hidden");
            document.getElementById("main-content").classList.toggle("content-expanded");
        });
    </script>
</body>
</html>
