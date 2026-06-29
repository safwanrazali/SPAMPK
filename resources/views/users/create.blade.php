@extends('layouts.app')

@section('title', 'Pengguna Baharu')
@section('crumb', 'Pentadbiran / Pengguna')

@section('content')
    <div class="page-head">
        <div><h1>Cipta Pengguna</h1><p>Tambah akaun pengguna baharu.</p></div>
        <a href="{{ route('users.index') }}" class="btn btn--ghost">Kembali</a>
    </div>

    <div class="card">
        <div class="card__body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Nama Penuh</label>
                        <input type="text" name="name" id="name" class="input" value="{{ old('name') }}" required>
                    </div>
                    <div class="field">
                        <label for="username">Nama Pengguna</label>
                        <input type="text" name="username" id="username" class="input" value="{{ old('username') }}" required>
                        <span class="hint">4–50 aksara: huruf, nombor, - dan _ sahaja.</span>
                    </div>
                    <div class="field">
                        <label for="role">Peranan</label>
                        <select name="role" id="role" class="select" required>
                            <option value="">— Pilih peranan —</option>
                            @foreach (App\Models\User::ROLES as $key => $label)
                                <option value="{{ $key }}" @selected(old('role') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="is_active">Status Akaun</label>
                        <select name="is_active" id="is_active" class="select">
                            <option value="1">Aktif</option>
                            <option value="0">Nyahaktif</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="password">Kata Laluan</label>
                        <input type="password" name="password" id="password" class="input" required>
                        <span class="hint">Min. 8 aksara, mengandungi huruf besar, huruf kecil, nombor &amp; aksara khas.</span>
                    </div>
                    <div class="field">
                        <label for="password_confirmation">Sahkan Kata Laluan</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="input" required>
                    </div>
                </div>
                <div class="toolbar">
                    <button type="submit" class="btn btn--primary">Simpan Pengguna</button>
                    <a href="{{ route('users.index') }}" class="btn btn--ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
