<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <h2>Aplikasi Peminjaman dan Penjadwalan Studio Musik</h2>
        <p class="subtext">Politeknik Negeri Banyuwangi</p>
        <img src="{{ asset('img/logo.png') }}" alt="Logo">

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="NIM" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required>
                    <i class="fa fa-eye-slash toggle-password" onclick="togglePassword()"></i>
                </div>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger" style="color: red">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif        
        
        <a class="forgot-password" href="#">Lupa Kata Sandi?</a>
        <p class="mt-2">Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar</a></p>
        

            <button type="submit" class="login-button">Login</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            let password = document.getElementById("password");
            let icon = document.querySelector(".toggle-password");
            if (password.type === "password") {
                password.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                password.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>
