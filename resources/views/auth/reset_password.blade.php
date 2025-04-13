<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">
</head>
<body>
    <div class="container">
        <h2>Reset Kata Sandi</h2>
        <p class="subtext">Masukkan kata sandi baru untuk akun Anda.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Token untuk validasi link reset -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email tersembunyi -->
            <input type="hidden" name="email" value="{{ $email }}">

            <!-- Password Baru -->
            <div class="input-group">
                <label for="password">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Konfirmasi Password -->
            <div class="input-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <!-- Error Handling -->
            @if ($errors->any())
                <div class="alert alert-danger" style="color: red">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="login-button">Reset Kata Sandi</button>
        </form>

        <p class="mt-2">Ingat passwordmu? <a href="{{ route('login') }}" class="register-link">Kembali ke Login</a></p>
    </div>
</body>
</html>
