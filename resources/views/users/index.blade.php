@extends('layouts.app')

@section('title', 'Pengurusan Pengguna')
@section('crumb', 'Pentadbiran')

@section('content')
    <div class="page-head">
        <div>
            <h1>Pengurusan Pengguna</h1>
            <p>Urus akaun dan peranan pengguna sistem.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn--primary">+ Pengguna Baharu</a>
    </div>

    <div class="card">
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
                    @forelse ($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td class="mono">{{ $user->username }}</td>
                            <td>{{ $user->roleLabel() }}</td>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge badge--success">Aktif</span>
                                @else
                                    <span class="badge badge--muted">Nyahaktif</span>
                                @endif
                            </td>
                            <td>
                                <div class="toolbar">
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn--ghost btn--sm">Sunting</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          data-confirm="Tukar status akaun ini?">
                                        @csrf @method('DELETE')
                                        <button class="btn btn--ghost btn--sm">
                                            {{ $user->is_active ? 'Nyahaktif' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted text-center">Tiada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pager">{{ $users->links() }}</div>
@endsection
