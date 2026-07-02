@extends('layouts.app')

@section('title', 'Penyerahan')
@section('crumb', 'SPAMPK')

@section('content')
    <div class="page-head">
        <div>
            <h1>Penyerahan</h1>
            <p>Status penyerahan laporan untuk semakan &amp; kelulusan. Paparan ini masih templat sementara.</p>
        </div>
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Senarai Penyerahan</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Rujukan</th>
                        <th>Agensi</th>
                        <th>Status</th>
                        <th>Tarikh</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $s)
                        <tr>
                            <td class="mono">{{ $s['rujukan'] }}</td>
                            <td>{{ $s['agensi'] }}</td>
                            <td><span class="badge badge--info">{{ $s['status'] }}</span></td>
                            <td>{{ $s['tarikh']->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted text-center">Tiada penyerahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection