@extends('layouts.app')

@section('title', 'Laporan ' . $report->kod_rujukan)
@section('crumb', 'Laporan')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $report->nama_agensi }}</h1>
            <p class="mono">{{ $report->kod_rujukan }}</p>
        </div>
        <div class="toolbar">
            <span class="badge {{ $report->workflowBadge() }}">{{ $report->workflowLabel() }}</span>
        </div>
    </div>

    {{-- Bar tindakan ikut peranan & status --}}
    <div class="card section-block">
        <div class="card__body">
            <div class="toolbar">
                <a href="{{ route('reports.pdf', $report) }}" class="btn btn--ghost">Muat Turun PDF</a>

                @can('update', $report)
                    <a href="{{ route('reports.edit', $report) }}" class="btn btn--ghost">Sunting</a>
                @endcan

                @can('submit', $report)
                    <form method="POST" action="{{ route('reports.submit', $report) }}"
                          data-confirm="Hantar laporan ini untuk semakan?">
                        @csrf
                        <button class="btn btn--primary">Hantar untuk Semakan</button>
                    </form>
                @endcan

                <span class="spacer"></span>

                @can('delete', $report)
                    <form method="POST" action="{{ route('reports.destroy', $report) }}"
                          data-confirm="Padam laporan ini secara kekal?">
                        @csrf @method('DELETE')
                        <button class="btn btn--danger btn--sm">Padam</button>
                    </form>
                @endcan
            </div>
        </div>
    </div>

    {{-- Kotak semakan untuk Penyelaras --}}
    @can('review', $report)
        <div class="card section-block">
            <div class="card__head"><span class="card__title">Semakan Penyelaras</span></div>
            <div class="card__body">
                <form method="POST" action="{{ route('reports.review', $report) }}">
                    @csrf
                    <div class="field">
                        <label for="catatan_semakan">Catatan Semakan (pilihan)</label>
                        <textarea name="catatan_semakan" id="catatan_semakan" class="textarea" rows="3"></textarea>
                    </div>
                    <div class="toolbar">
                        <button name="keputusan" value="lulus" class="btn btn--success">Sahkan & Luluskan</button>
                        <button name="keputusan" value="pembetulan" class="btn btn--ghost">Kembalikan untuk Pembetulan</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    {{-- Maklum balas semakan terdahulu --}}
    @if ($report->catatan_semakan)
        <div class="review-box">
            <strong>Catatan Semakan:</strong> {{ $report->catatan_semakan }}
        </div>
    @endif

    {{-- Seksyen 1 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">1. Maklumat Laporan</span></div>
        <div class="card__body">
            <div class="report-meta">
                <div class="meta-item"><div class="k">Agensi</div><div class="v">{{ $report->nama_agensi }}</div></div>
                <div class="meta-item"><div class="k">Sektor</div><div class="v">{{ $report->sektor ?: '—' }}</div></div>
                <div class="meta-item"><div class="k">Tarikh</div><div class="v">{{ $report->tarikh_laporan?->format('d/m/Y') }}</div></div>
                <div class="meta-item"><div class="k">Status Analisis</div><div class="v">{{ $report->status_analisis ?: '—' }}</div></div>
                <div class="meta-item"><div class="k">Disediakan Oleh</div><div class="v">{{ $report->disediakan_oleh ?: '—' }}</div></div>
                <div class="meta-item"><div class="k">Disemak Oleh</div><div class="v">{{ $report->disemak_oleh ?: '—' }}</div></div>
            </div>
        </div>
    </div>

    {{-- Seksyen 2 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">2. Tujuan</span></div>
        <div class="card__body"><p>{{ $report->tujuan ?: '—' }}</p></div>
    </div>

    {{-- Seksyen 3 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">3. Status Data Diterima</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Komponen</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach ($report->status_data ?? [] as $row)
                    <tr><td>{{ $row['komponen'] }}</td><td>{{ $row['status'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
                @endforeach
                </tbody>
            </table>
            @if ($report->ringkasan_status_data)
                <p class="mt-3"><strong>Ringkasan:</strong> {{ $report->ringkasan_status_data }}</p>
            @endif
        </div>
    </div>

    {{-- Seksyen 4 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">4. Ringkasan Dapatan Inventori Kriptografi</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Dapatan</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach ($report->inventori ?? [] as $row)
                    <tr><td>{{ $row['dapatan'] }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Seksyen 5 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">5. Dapatan Kategori Risiko Migrasi PQC</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Kategori Risiko</th><th>Dikenal Pasti</th><th>Catatan</th></tr></thead>
                <tbody>
                @foreach ($report->kategori_risiko ?? [] as $row)
                    <tr><td>{{ $row['kategori'] }}</td><td>{{ $row['dikenal_pasti'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
                @endforeach
                </tbody>
            </table>
            @if ($report->ringkasan_kategori_risiko)
                <p class="mt-3"><strong>Ringkasan:</strong> {{ $report->ringkasan_kategori_risiko }}</p>
            @endif
        </div>
    </div>

    {{-- Seksyen 6 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">6. Tahap Risiko & Keutamaan Aset</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Aset/Sistem</th><th>Kategori Risiko</th><th>Tahap Risiko</th><th>Keutamaan</th><th>Catatan</th></tr></thead>
                <tbody>
                @forelse ($report->aset ?? [] as $row)
                    <tr>
                        <td>{{ $row['nama_aset'] ?: '—' }}</td>
                        <td>{{ $row['kategori_risiko'] ?: '—' }}</td>
                        <td>{{ $row['tahap_risiko'] ?: '—' }}</td>
                        <td>{{ $row['keutamaan'] ?: '—' }}</td>
                        <td>{{ $row['catatan'] ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="muted text-center">Tiada rekod.</td></tr>
                @endforelse
                </tbody>
            </table>
            @if ($report->ringkasan_keutamaan_aset)
                <p class="mt-3"><strong>Ringkasan:</strong> {{ $report->ringkasan_keutamaan_aset }}</p>
            @endif
        </div>
    </div>

    {{-- Seksyen 7 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">7. Catatan Analisis & Limitasi Data</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Isu / Limitasi</th><th>Kesan</th><th>Catatan</th></tr></thead>
                <tbody>
                @forelse ($report->limitasi ?? [] as $row)
                    <tr><td>{{ $row['isu'] ?: '—' }}</td><td>{{ $row['kesan'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
                @empty
                    <tr><td colspan="3" class="muted text-center">Tiada rekod.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Seksyen 8 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">8. Cadangan Tindakan Susulan</span></div>
        <div class="card__body table-wrap">
            <table class="table table--bordered">
                <thead><tr><th>Cadangan</th><th>Pihak</th><th>Keutamaan</th></tr></thead>
                <tbody>
                @forelse ($report->cadangan ?? [] as $row)
                    <tr><td>{{ $row['cadangan'] ?: '—' }}</td><td>{{ $row['pihak'] ?: '—' }}</td><td>{{ $row['keutamaan'] ?: '—' }}</td></tr>
                @empty
                    <tr><td colspan="3" class="muted text-center">Tiada rekod.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Seksyen 9 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">9. Kesimpulan</span></div>
        <div class="card__body"><p>{{ $report->kesimpulan ?: '—' }}</p></div>
    </div>

    {{-- Seksyen 10 --}}
    <div class="card section-block">
        <div class="card__head"><span class="card__title">10. Pengesahan Laporan</span></div>
        <div class="card__body">
            <div class="report-meta">
                <div class="meta-item"><div class="k">Disediakan Oleh</div><div class="v">{{ $report->disediakan_oleh ?: '—' }}</div></div>
                <div class="meta-item"><div class="k">Disemak Oleh</div><div class="v">{{ $report->disemak_oleh ?: '—' }}</div></div>
                <div class="meta-item"><div class="k">Status</div><div class="v">{{ $report->workflowLabel() }}</div></div>
            </div>
        </div>
    </div>
@endsection
