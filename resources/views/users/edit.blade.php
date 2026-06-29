@extends('layouts.app')

@section('title', 'Sunting Pengguna')
@section('crumb', 'Pentadbiran / Pengguna')

@section('content')
    <div class="page-head">
        <div><h1>Sunting Pengguna</h1><p>{{ $user->username }}</p></div>
        <a href="{{ route('users.index') }}" class="btn btn--ghost">Kembali</a>
    </div>

    <div class="card">
        <div class="card__body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf @method('PUT')
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama Penuh</label>
                        <input type="text" name="name" id="name" class="input" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="field">
                        <label for="username">Nama Pengguna</label>
                        <input type="text" name="username" id="username" class="input" value="{{ old('username', $user->username) }}" required>
                        <span class="hint">4–50 aksara: huruf, nombor, - dan _ sahaja.</span>
                    </div>
                    <div class="field">
                        <label for="role">Peranan</label>
                        <select name="role" id="role" class="select" required>
                            @foreach (App\Models\User::ROLES as $key => $label)
                                <option value="{{ $key }}" @selected(old('role', $user->role) === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="is_active">Status Akaun</label>
                        <select name="is_active" id="is_active" class="select">
                            <option value="1" @selected($user->is_active)>Aktif</option>
                            <option value="0" @selected(! $user->is_active)>Nyahaktif</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="password">Kata Laluan Baharu</label>
                        <input type="password" name="password" id="password" class="input">
                        <span class="hint">Biar kosong untuk kekalkan. Jika diisi: min. 8 aksara, huruf besar, huruf kecil, nombor &amp; aksara khas.</span>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Sahkan Kata Laluan</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input">
                    </div>
                </div>
                <div class="toolbar">
                    <button type="submit" class="btn btn--primary">Kemas Kini</button>
                    <a href="{{ route('users.index') }}" class="btn btn--ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
