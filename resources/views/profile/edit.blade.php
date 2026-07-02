@extends('layouts.app')

@section('title', 'Profil Pengguna')
@section('crumb', 'Akaun')

@section('content')
    <div class="page-head">
        <div><h1>Profil Pengguna</h1><p>Kemas kini maklumat akaun & kata laluan anda.</p></div>
    </div>

    <div class="card">
        <div class="card__body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PUT')

                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama Penuh</label>
                        <input type="text" name="name" id="name" class="input"
                               value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label for="username">Nama Pengguna</label>
                        <input type="text" name="username" id="username" class="input"
                               value="{{ old('username', $user->username) }}" required>
                        <span class="hint">4–50 aksara: huruf, nombor, - dan _ sahaja.</span>
                    </div>
                    <div class="field">
                        <label>Peranan</label>
                        <input type="text" class="input" value="{{ $user->roleLabel() }}" disabled>
                        <span class="hint">Peranan hanya boleh diubah oleh Pentadbir Sistem.</span>
                    </div>
                </div>

                <div class="section-block__head" style="margin-top:1.5rem;">
                    <h3>Tukar Kata Laluan</h3>
                </div>
                <p class="muted">Biar kosong jika tidak mahu menukar kata laluan.</p>

                <div class="form-grid">
                    <div class="field">
                        <label for="current_password">Kata Laluan Semasa</label>
                        <input type="password" name="current_password" id="current_password" class="input" autocomplete="current-password">
                    </div>
                    <div class="field"></div>
                    <div class="field">
                        <label for="password">Kata Laluan Baharu</label>
                        <input type="password" name="password" id="password" class="input" autocomplete="new-password">
                        <span class="hint">Min. 8 aksara, huruf besar, huruf kecil, nombor &amp; aksara khas.</span>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Sahkan Kata Laluan Baharu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input" autocomplete="new-password">
                    </div>
                </div>

                <div class="toolbar">
                    <button type="submit" class="btn btn--primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection