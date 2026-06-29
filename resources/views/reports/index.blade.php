@extends('layouts.app')

@section('title', 'Laporan')
@section('crumb', 'SPAMPK')

@section('content')
    <div class="page-head">
        <div>
            <h1>Laporan Analisis</h1>
            <p>Senarai laporan migrasi PQC.</p>
        </div>
        @can('create', App\Models\Report::class)
            <a href="{{ route('reports.create') }}" class="btn btn--primary">+ Laporan Baharu</a>
        @endcan
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Senarai</span>
            <form method="GET" class="toolbar">
                <select name="status" class="select" onchange="this.form.submit()">
                    <option value="">Semua status</option>
                    @foreach (App\Models\Report::WORKFLOW as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kod Rujukan</th>
                        <th>Agensi</th>
                        <th>Sektor</th>
                        <th>Status</th>
                        <th>Disediakan Oleh</th>
                        <th>Tarikh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reports as $r)
                        <tr>
                            <td class="mono">{{ $r->kod_rujukan }}</td>
                            <td><strong>{{ $r->nama_agensi }}</strong></td>
                            <td>{{ $r->sektor ?: '—' }}</td>
                            <td><span class="badge {{ $r->workflowBadge() }}">{{ $r->workflowLabel() }}</span></td>
                            <td>{{ $r->author?->name ?? '—' }}</td>
                            <td>{{ $r->tarikh_laporan?->format('d/m/Y') }}</td>
                            <td><a href="{{ route('reports.show', $r) }}" class="btn btn--ghost btn--sm">Lihat</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="muted text-center">Tiada laporan ditemui.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="pager">{{ $reports->links() }}</div>
@endsection
