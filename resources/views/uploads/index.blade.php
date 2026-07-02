@extends('layouts.app')

@section('title', 'Muat Naik Fail')
@section('crumb', 'SPAMPK')

@section('content')
    <div class="page-head">
        <div>
            <h1>Muat Naik Fail</h1>
            <p>Muat naik fail/data (docx, pdf, xlsx dan lain-lain) sebagai data laporan. Paparan ini masih templat sementara.</p>
        </div>
        <button type="button" class="btn btn--primary" disabled>+ Muat Naik Fail</button>
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Fail Dimuat Naik</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Fail</th>
                        <th>Jenis</th>
                        <th>Saiz</th>
                        <th>Tarikh Muat Naik</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $file)
                        <tr>
                            <td><strong>{{ $file['nama'] }}</strong></td>
                            <td>{{ $file['jenis'] }}</td>
                            <td>{{ $file['saiz'] }}</td>
                            <td>{{ $file['dimuat_naik']->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="muted text-center">Tiada fail dimuat naik.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection