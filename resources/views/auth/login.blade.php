<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <title>Log Masuk &middot; SPAMPK</title>
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>
<body>
<div class="login">
    <section class="login__brand">
        <img class="login__logo" src="{{ asset('images/spampk-logo.png') }}" alt="SPAMPK">
        <p>Sistem Pelaporan Automatik Migrasi Pasca-Kuantum — platform luar talian bagi penjanaan laporan analisis data migrasi PQC.</p>
        <ul class="login__points">
            <li>Penjanaan laporan analisis 10 seksyen secara automatik</li>
            <li>Kawalan capaian berasaskan peranan</li>
            <li>Beroperasi sepenuhnya tanpa sambungan Internet</li>
        </ul>
        <div class="tagline">Bahagian Migrasi PQC</div>
    </section>

    <section class="login__panel">
        <div class="login__card">
            <img class="login__card-mark" src="{{ asset('images/spampk-mark.png') }}" alt="SPAMPK">
            <h2>Log Masuk</h2>
            <p class="sub">Masukkan nama pengguna dan kata laluan anda.</p>

            @if ($errors->any())
                <div class="alert alert--error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="field">
                    <label for="username">Nama Pengguna</label>
                    <input type="text" name="username" id="username" class="input"
                           value="{{ old('username') }}" required autofocus autocomplete="username">
                </div>
                <div class="field">
                    <label for="password">Kata Laluan</label>
                    <input type="password" name="password" id="password" class="input"
                           required autocomplete="current-password">
                </div>
                <div class="field checkrow">
                    <input type="checkbox" name="remember" id="remember" value="1">
                    <label for="remember">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn--primary btn--block">Log Masuk</button>
            </form>
        </div>
    </section>
</div>
</body>
</html>
