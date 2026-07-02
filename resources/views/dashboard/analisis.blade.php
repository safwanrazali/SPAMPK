@extends('layouts.app')

@section('title', 'Papan Pemuka')
@section('crumb', 'SPAMPK')

@section('content')
    <div class="page-head">
        <div>
            <h1>Selamat datang, {{ $user->name }}</h1>
            <p>{{ $user->roleLabel() }}</p>
        </div>
        @can('create', App\Models\Report::class)
            <a href="{{ route('reports.create') }}" class="btn btn--primary">+ Laporan Baharu</a>
        @endcan
    </div>

    <div class="stat-grid">
        <div class="stat stat--bar">
            <div class="stat__k">Jumlah Laporan Saya</div>
            <div class="stat__v stat__v--accent">{{ $stats['jumlah'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Draf</div>
            <div class="stat__v">{{ $stats['draf'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Menunggu Semakan</div>
            <div class="stat__v">{{ $stats['dihantar'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Diluluskan</div>
            <div class="stat__v">{{ $stats['diluluskan'] }}</div>
        </div>
        <div class="stat">
            <div class="stat__k">Perlu Pembetulan</div>
            <div class="stat__v">{{ $stats['pembetulan'] }}</div>
        </div>
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Laporan Saya Terkini</span>
            <a href="{{ route('reports.index') }}" class="btn btn--ghost btn--sm">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kod Rujukan</th>
                        <th>Agensi</th>
                        <th>Status</th>
                        <th>Tarikh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terkini as $r)
                        <tr>
                            <td class="mono">{{ $r->kod_rujukan }}</td>
                            <td>{{ $r->nama_agensi }}</td>
                            <td><span class="badge {{ $r->workflowBadge() }}">{{ $r->workflowLabel() }}</span></td>
                            <td>{{ $r->tarikh_laporan?->format('d/m/Y') }}</td>
                            <td><a href="{{ route('reports.show', $r) }}" class="btn btn--ghost btn--sm">Lihat</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted text-center">Anda belum mencipta sebarang laporan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection