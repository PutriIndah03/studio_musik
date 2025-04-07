<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <h2>Lupa Kata Sandi</h2>
        <p class="subtext">Masukkan NIM Anda, kami akan mengirimkan tautan reset password ke email Anda.</p>

        <!-- Form Lupa Kata Sandi -->
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="input-group">
                <label for="nim">NIM</label>
                <input type="text" id="nim" name="nim" placeholder="Masukkan NIM" required>
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
        </form>

        <a href="{{ route('login') }}" class="back-link">Kembali ke Login</a>
    </div>
</body>
</html>
