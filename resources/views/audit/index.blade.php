@extends('layouts.app')

@section('title', 'Log Audit')
@section('crumb', 'Pentadbiran')

@section('content')
    <div class="page-head">
        <div>
            <h1>Log Audit</h1>
            <p>Rekod aktiviti sistem. Paparan ini masih templat sementara.</p>
        </div>
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Aktiviti Terkini</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Masa</th>
                        <th>Pengguna</th>
                        <th>Tindakan</th>
                        <th>Butiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="mono">{{ $log['masa']->format('d/m/Y H:i') }}</td>
                            <td>{{ $log['pengguna'] }}</td>
                            <td>{{ $log['tindakan'] }}</td>
                            <td class="muted">{{ $log['butiran'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted text-center">Tiada rekod log.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection