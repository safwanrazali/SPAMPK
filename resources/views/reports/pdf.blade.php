<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 28mm 18mm 24mm 18mm; }
        * { font-family: DejaVu Sans, sans-serif; }
        body { font-size: 10.5px; color: #1b2a3d; line-height: 1.5; }
        .doc-head { border-bottom: 2px solid #127a74; padding-bottom: 10px; margin-bottom: 16px; }
        .doc-head .org { font-size: 9px; letter-spacing: 1px; text-transform: uppercase; color: #6b7c93; }
        .doc-head h1 { font-size: 16px; color: #0d1b2a; margin: 4px 0 2px; }
        .doc-head .kod { font-size: 10px; color: #127a74; font-weight: bold; }
        h2 { font-size: 12px; color: #0d1b2a; border-left: 4px solid #127a74; padding-left: 8px; margin: 18px 0 8px; }
        p { margin: 0 0 6px; }
        table { width: 100%; border-collapse: collapse; margin: 4px 0 8px; }
        th, td { border: 1px solid #c9d4e0; padding: 5px 7px; text-align: left; vertical-align: top; }
        th { background: #0d1b2a; color: #fff; font-size: 9.5px; }
        .meta td { border: none; padding: 2px 0; }
        .meta .k { color: #6b7c93; width: 35%; }
        .meta .v { font-weight: bold; }
        .summary { background: #e7f3f1; border: 1px solid #bfe0db; padding: 6px 9px; margin-top: 4px; }
        .sign { width: 100%; margin-top: 14px; }
        .sign td { border: none; padding: 22px 10px 4px; width: 50%; }
        .sign .line { border-top: 1px solid #1b2a3d; padding-top: 4px; font-size: 9.5px; }
        .muted { color: #98a6b8; }
        .w15{width:15%} .w18{width:18%} .w33{width:33%} .w40{width:40%} .w45{width:45%} .w55{width:55%}
        .foot { position: fixed; bottom: -14mm; left: 0; right: 0; font-size: 8px; color: #98a6b8; text-align: center; }
    </style>
</head>
<body>
    <div class="foot">SPAMPK — Sistem Pelaporan Automatik Migrasi Pasca-Kuantum &middot; {{ $report->kod_rujukan }} &middot; Sulit</div>

    <div class="doc-head">
        <div class="org">Bahagian Migrasi PQC</div>
        <h1>Laporan Analisis Data Migrasi PQC</h1>
        <div class="kod">{{ $report->kod_rujukan }}</div>
    </div>

    <h2>1. Maklumat Laporan</h2>
    <table class="meta">
        <tr><td class="k">Nama Agensi / Entiti</td><td class="v">{{ $report->nama_agensi }}</td></tr>
        <tr><td class="k">Sektor</td><td class="v">{{ $report->sektor ?: '—' }}</td></tr>
        <tr><td class="k">Tarikh</td><td class="v">{{ $report->tarikh_laporan?->format('d/m/Y') }}</td></tr>
        <tr><td class="k">Kod Rujukan</td><td class="v">{{ $report->kod_rujukan }}</td></tr>
        <tr><td class="k">Disediakan Oleh</td><td class="v">{{ $report->disediakan_oleh ?: '—' }}</td></tr>
        <tr><td class="k">Disemak Oleh</td><td class="v">{{ $report->disemak_oleh ?: '—' }}</td></tr>
        <tr><td class="k">Status Analisis</td><td class="v">{{ $report->status_analisis ?: '—' }}</td></tr>
    </table>

    <h2>2. Tujuan</h2>
    <p>{{ $report->tujuan ?: '—' }}</p>

    <h2>3. Status Data Diterima</h2>
    <table>
        <thead><tr><th class="w40">Komponen</th><th class="w18">Status</th><th>Catatan</th></tr></thead>
        <tbody>
        @foreach ($report->status_data ?? [] as $row)
            <tr><td>{{ $row['komponen'] }}</td><td>{{ $row['status'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @if ($report->ringkasan_status_data)
        <div class="summary"><strong>Ringkasan:</strong> {{ $report->ringkasan_status_data }}</div>
    @endif

    <h2>4. Ringkasan Dapatan Inventori Kriptografi</h2>
    <table>
        <thead><tr><th class="w45">Dapatan</th><th>Catatan</th></tr></thead>
        <tbody>
        @foreach ($report->inventori ?? [] as $row)
            <tr><td>{{ $row['dapatan'] }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
        @endforeach
        </tbody>
    </table>

    <h2>5. Dapatan Kategori Risiko Migrasi PQC</h2>
    <table>
        <thead><tr><th class="w45">Kategori Risiko</th><th class="w15">Dikenal Pasti</th><th>Catatan</th></tr></thead>
        <tbody>
        @foreach ($report->kategori_risiko ?? [] as $row)
            <tr><td>{{ $row['kategori'] }}</td><td>{{ $row['dikenal_pasti'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @if ($report->ringkasan_kategori_risiko)
        <div class="summary"><strong>Ringkasan:</strong> {{ $report->ringkasan_kategori_risiko }}</div>
    @endif

    <h2>6. Tahap Risiko & Keutamaan Aset</h2>
    <table>
        <thead><tr><th>Aset/Sistem</th><th>Kategori Risiko</th><th>Tahap Risiko</th><th>Keutamaan</th><th>Catatan</th></tr></thead>
        <tbody>
        @forelse ($report->aset ?? [] as $row)
            <tr><td>{{ $row['nama_aset'] ?: '—' }}</td><td>{{ $row['kategori_risiko'] ?: '—' }}</td><td>{{ $row['tahap_risiko'] ?: '—' }}</td><td>{{ $row['keutamaan'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
        @empty
            <tr><td colspan="5" class="muted">Tiada rekod.</td></tr>
        @endforelse
        </tbody>
    </table>
    @if ($report->ringkasan_keutamaan_aset)
        <div class="summary"><strong>Ringkasan:</strong> {{ $report->ringkasan_keutamaan_aset }}</div>
    @endif

    <h2>7. Catatan Analisis & Limitasi Data</h2>
    <table>
        <thead><tr><th class="w33">Isu / Limitasi</th><th class="w33">Kesan</th><th>Catatan</th></tr></thead>
        <tbody>
        @forelse ($report->limitasi ?? [] as $row)
            <tr><td>{{ $row['isu'] ?: '—' }}</td><td>{{ $row['kesan'] ?: '—' }}</td><td>{{ $row['catatan'] ?: '—' }}</td></tr>
        @empty
            <tr><td colspan="3" class="muted">Tiada rekod.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>8. Cadangan Tindakan Susulan</h2>
    <table>
        <thead><tr><th class="w55">Cadangan</th><th>Pihak</th><th>Keutamaan</th></tr></thead>
        <tbody>
        @forelse ($report->cadangan ?? [] as $row)
            <tr><td>{{ $row['cadangan'] ?: '—' }}</td><td>{{ $row['pihak'] ?: '—' }}</td><td>{{ $row['keutamaan'] ?: '—' }}</td></tr>
        @empty
            <tr><td colspan="3" class="muted">Tiada rekod.</td></tr>
        @endforelse
        </tbody>
    </table>

    <h2>9. Kesimpulan</h2>
    <p>{{ $report->kesimpulan ?: '—' }}</p>

    <h2>10. Pengesahan Laporan</h2>
    <table class="sign">
        <tr>
            <td><div class="line">Disediakan oleh (Pegawai Analisis)<br><strong>{{ $report->disediakan_oleh ?: '—' }}</strong></div></td>
            <td><div class="line">Disemak oleh (Pegawai Penyelaras Analisis)<br><strong>{{ $report->disemak_oleh ?: '—' }}</strong></div></td>
        </tr>
    </table>
</body>
</html>
