<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6"> <br><br><br><br><br><br>
                <div class="card p-4">
                    <div class="card-body">
                        <img src="{{ asset('img/logo.png') }}" alt="Logo">
                        <h2 class="text-center fw-bold mb-3">Register Mahasiswa</h2>
                        <form action="{{ Route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" name="nim" class="form-control" required>
                                @error('nim')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prodi</label>
                                <select name="prodi" class="form-select" required>
                                    <option value="" disabled selected>Pilih Program Studi</option>
                                    <option value="Teknologi Rekayasa Perangkat Lunak">Teknologi Rekayasa Perangkat Lunak</option>
                                    <option value="Manajemen Bisnis Pariwisata">Manajemen Bisnis Pariwisata</option>
                                    <option value="Bahasa Inggris Mesin">Teknik Mesin</option>
                                    <option value="Teknologi Rekayasa Komputer">Teknologi Rekayasa Komputer</option>
                                    <option value="Teknik Sipil">Teknik Sipil</option>
                                    <option value="Agribisnis">Agribisnis</option>
                                    <option value="Bisnis Digital">Bisnis Digital</option>
                                    <option value="Teknologi Pengolahan Hasil Ternak">Teknologi Pengolahan Hasil Ternak</option>
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">No HP</label>
                                    <input type="text" name="no_hp" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Kata Sandi</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" class="form-control" id="password" required>
                                        <i class="fa fa-eye-slash toggle-password" onclick="togglePassword('password')" style="position: absolute; right: 10px; top: 10px; cursor: pointer; font-weight: normal;"></i>
                                    </div>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Kata Sandi</label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                                        <i class="fa fa-eye-slash toggle-password" onclick="togglePassword('password_confirmation')" style="position: absolute; right: 10px; top: 10px; cursor: pointer; font-weight: normal;"></i>
                                    </div>
                                </div>
                            </div>

                            <p class="text-muted text-center">
                                Sudah punya akun? <a href="{{ route('login') }}" class="login-link"><i>Login</i></a>
                            </p>

                            <button type="submit" class="btn btn-register w-100 py-2">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(id) {
            var passwordField = document.getElementById(id);
            var icon = passwordField.nextElementSibling;

            // Toggle the password field type
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>
</body>
</html>
