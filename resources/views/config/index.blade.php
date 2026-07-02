@extends('layouts.app')

@section('title', 'Sistem Konfigurasi')
@section('crumb', 'Pentadbiran')

@section('content')
    <div class="page-head">
        <div>
            <h1>Sistem Konfigurasi</h1>
            <p>Tetapan am sistem. Paparan ini masih templat sementara.</p>
        </div>
    </div>

    <div class="card">
        <div class="card__head">
            <span class="card__title">Tetapan Am</span>
        </div>
        <div class="card__body">
            <div class="report-meta">
                @foreach ($settings as $s)
                    <div class="meta-item">
                        <div class="k">{{ $s['label'] }}</div>
                        <div class="v">{{ $s['nilai'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection