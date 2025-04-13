<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}"" type="image/png">
</head>
<body>
    <div class="container">
        <h2>Aplikasi Peminjaman dan Penjadwalan Studio Musik</h2>
        <p class="subtext">Politeknik Negeri Banyuwangi</p>
        <img src="{{ asset('img/logo.png') }}" alt="Logo">

        <!-- Form Login -->
        <form action="{{ route('login') }}" method="POST">
            @csrf

            @if (session('status'))
            <div class="alert alert-success" style="color: green">
                <p>{{ session('status') }}</p>
            </div>
        @endif

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="NIM" required onblur="checkMultipleAccounts()">
            </div>

            <!-- Pilihan Role (Tampil jika username memiliki lebih dari satu akun) -->
            <div class="input-group" id="roleSelection" style="display: none;">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <!-- Options akan diisi oleh JavaScript -->
                </select>
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

            <a class="forgot-password" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
            <p class="mt-2">Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar</a></p>

            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
    <script>
          sessionStorage.setItem("newLogin", "true");
        let debounceTimer;
    
        document.getElementById("username").addEventListener("input", function () {
            clearTimeout(debounceTimer);
            let username = this.value.trim();
            let roleSelection = document.getElementById("roleSelection");
    
            if (username === "") {
                roleSelection.style.display = "none";
                return;
            }
    
            // Menggunakan debounce untuk menunda fetch
            debounceTimer = setTimeout(() => {
                fetch(`{{ route('check.accounts') }}?username=${username}`)
                    .then(response => response.json())
                    .then(data => {
                        let roleDropdown = document.getElementById("role");
    
                        if (data.length > 1) {
                            roleDropdown.innerHTML = "";
                            data.forEach(role => {
                                let option = document.createElement("option");
                                option.value = role;
                                option.textContent = role.charAt(0).toUpperCase() + role.slice(1);
                                roleDropdown.appendChild(option);
                            });
                            roleSelection.style.display = "block";
                        } else {
                            roleSelection.style.display = "none";
                        }
                    })
                    .catch(error => console.error("Error fetching roles:", error));
            }, 300); // Menunggu 300ms setelah pengguna berhenti mengetik
        });
    
        function togglePassword() {
            let password = document.getElementById("password");
            let icon = document.querySelector(".toggle-password");
            password.type = password.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        }
    </script>
    
</body>
</html>
