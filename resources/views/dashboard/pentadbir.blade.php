@extends('layouts.app')

@section('title', 'Papan Pemuka')
@section('crumb', 'SPAMPK')

@section('content')
    <div class="page-head">
        <div>
            <h1>Selamat datang, {{ $user->name }}</h1>
            <p>{{ $user->roleLabel() }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn--primary">+ Pengguna Baharu</a>
    </div>

    <div class="stat-grid">
        <div class="stat stat--bar">
            <div class="stat__k">Jumlah Pengguna</div>
            <div class="stat__v stat__v--accent">{{ $stats['jumlah'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Akaun Aktif</div>
            <div class="stat__v">{{ $stats['aktif'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Akaun Nyahaktif</div>
            <div class="stat__v">{{ $stats['tidak_aktif'] }}</div>
        </div>
        @foreach ($mengikutPeranan as $row)
            <div class="stat">
                <div class="stat__k">{{ $row['label'] }}</div>
                <div class="stat__v">{{ $row['jumlah'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Pengguna Terkini</span>
            <a href="{{ route('users.index') }}" class="btn btn--ghost btn--sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Nama Pengguna</th>
                        <th>Peranan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terkini as $u)
                        <tr>
                            <td><strong>{{ $u->name }}</strong></td>
                            <td class="mono" style="text-transform: capitalize;">{{ $u->username }}</td>
                            <td>{{ $u->roleLabel() }}</td>
                            <td>
                                @if ($u->is_active)
                                    <span class="badge badge--success">Aktif</span>
                                @else
                                    <span class="badge badge--muted">Nyahaktif</span>
                                @endif
                            </td>
                            <td><a href="{{ route('users.edit', $u) }}" class="btn btn--ghost btn--sm">Sunting</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted text-center">Tiada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection