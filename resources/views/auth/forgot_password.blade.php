<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>
<body>
    <div class="container">
        <h2>Lupa Kata Sandi</h2>
        <p class="subtext">Masukkan NIM Anda, kami akan mengirimkan tautan reset password ke email Anda.</p>

        <!-- Form Lupa Password -->
        <form action="{{ route('password.email') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" placeholder="Masukkan NIM" required oninput="checkMultipleAccounts()">
            </div>

            <!-- Pilihan Role (jika multi akun) -->
            <div class="input-group" id="roleSelection" style="display: none;">
                <label for="role">Role</label>
                <select id="role" name="role"></select>
            </div>

            @if (session('status'))
                <div class="alert alert-success" style="color: green">
                    <p>{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" style="color: red">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <button type="submit" class="login-button">Kirim Link Reset</button>
            <p class="mt-2">Ingat passwordmu? <a href="{{ route('login') }}" class="register-link">Kembali ke Login</a></p>
        </form>
    </div>

    <script>
        let debounceTimer;
        function checkMultipleAccounts() {
            clearTimeout(debounceTimer);
            let nim = document.getElementById("nim").value.trim();
            let roleSelection = document.getElementById("roleSelection");

            if (nim === "") {
                roleSelection.style.display = "none";
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`/check-accounts?username=${nim}`)
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
            }, 300);
        }
    </script>
</body>
</html>

