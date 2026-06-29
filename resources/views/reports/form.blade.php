@extends('layouts.app')

@section('title', $mode === 'create' ? 'Laporan Baharu' : 'Sunting Laporan')
@section('crumb', 'Laporan')

@php
    $action = $mode === 'create' ? route('reports.store') : route('reports.update', $report);
@endphp

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $mode === 'create' ? 'Cipta Laporan' : 'Sunting Laporan' }}</h1>
            <p>Lengkapkan 10 seksyen templat laporan analisis migrasi PQC.</p>
        </div>
        <a href="{{ $mode === 'create' ? route('reports.index') : route('reports.show', $report) }}" class="btn btn--ghost">Kembali</a>
    </div>

    <form method="POST" action="{{ $action }}">
        @csrf
        @if ($mode === 'edit') @method('PUT') @endif

        {{-- Seksyen 1 — Maklumat Laporan --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">1</span><h3>Maklumat Laporan</h3></div>
                <div class="form-grid">
                    <div class="field">
                        <label for="nama_agensi">Nama Agensi / Entiti</label>
                        <input type="text" name="nama_agensi" id="nama_agensi" class="input"
                               value="{{ old('nama_agensi', $report->nama_agensi) }}" required>
                    </div>
                    <div class="field">
                        <label for="sektor">Sektor</label>
                        <input type="text" name="sektor" id="sektor" class="input" value="{{ old('sektor', $report->sektor) }}">
                    </div>
                    <div class="field">
                        <label for="tarikh_laporan">Tarikh</label>
                        <input type="date" name="tarikh_laporan" id="tarikh_laporan" class="input"
                               value="{{ old('tarikh_laporan', optional($report->tarikh_laporan)->format('Y-m-d')) }}" required>
                    </div>
                    <div class="field">
                        <label for="status_analisis">Status Analisis</label>
                        <select name="status_analisis" id="status_analisis" class="select" required>
                            <option value="">— Pilih —</option>
                            @foreach (App\Models\Report::STATUS_ANALISIS_OPTS as $opt)
                                <option value="{{ $opt }}" @selected(old('status_analisis', $report->status_analisis) === $opt)>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="disediakan_oleh">Disediakan Oleh</label>
                        <input type="text" name="disediakan_oleh" id="disediakan_oleh" class="input"
                               value="{{ old('disediakan_oleh', $report->disediakan_oleh ?? auth()->user()->name) }}">
                    </div>
                    <div class="field">
                        <label for="disemak_oleh">Disemak Oleh</label>
                        <input type="text" name="disemak_oleh" id="disemak_oleh" class="input"
                               value="{{ old('disemak_oleh', $report->disemak_oleh) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Seksyen 2 — Tujuan --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">2</span><h3>Tujuan</h3></div>
                <div class="field">
                    <textarea name="tujuan" class="textarea" rows="3">{{ old('tujuan', $report->tujuan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seksyen 3 — Status Data Diterima --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">3</span><h3>Status Data Diterima</h3></div>
                <div class="table-wrap">
                    <table class="table table--bordered">
                        <thead><tr><th>Komponen</th><th>Status</th><th>Catatan</th></tr></thead>
                        <tbody>
                        @foreach (($report->status_data ?: App\Models\Report::defaultStatusData()) as $i => $row)
                            <tr>
                                <td>{{ $row['komponen'] }}</td>
                                <td>
                                    <select name="status_data[{{ $i }}][status]" class="select">
                                        <option value="">—</option>
                                        @foreach (App\Models\Report::STATUS_DATA_OPTS as $opt)
                                            <option value="{{ $opt }}" @selected(($row['status'] ?? '') === $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="status_data[{{ $i }}][catatan]" class="input" value="{{ $row['catatan'] ?? '' }}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="field mt-3">
                    <label>Ringkasan Status Data</label>
                    <textarea name="ringkasan_status_data" class="textarea" rows="2">{{ old('ringkasan_status_data', $report->ringkasan_status_data) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seksyen 4 — Ringkasan Dapatan Inventori Kriptografi --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">4</span><h3>Ringkasan Dapatan Inventori Kriptografi</h3></div>
                <div class="table-wrap">
                    <table class="table table--bordered">
                        <thead><tr><th>Dapatan</th><th>Catatan</th></tr></thead>
                        <tbody>
                        @foreach (($report->inventori ?: App\Models\Report::defaultInventori()) as $i => $row)
                            <tr>
                                <td>{{ $row['dapatan'] }}</td>
                                <td><input type="text" name="inventori[{{ $i }}][catatan]" class="input" value="{{ $row['catatan'] ?? '' }}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Seksyen 5 — Dapatan Kategori Risiko Migrasi PQC --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">5</span><h3>Dapatan Kategori Risiko Migrasi PQC</h3></div>
                <div class="table-wrap">
                    <table class="table table--bordered">
                        <thead><tr><th>Kategori Risiko</th><th>Dikenal Pasti</th><th>Catatan</th></tr></thead>
                        <tbody>
                        @foreach (($report->kategori_risiko ?: App\Models\Report::defaultKategoriRisiko()) as $i => $row)
                            <tr>
                                <td>{{ $row['kategori'] }}</td>
                                <td>
                                    <select name="kategori_risiko[{{ $i }}][dikenal_pasti]" class="select">
                                        <option value="">—</option>
                                        @foreach (App\Models\Report::YA_TIDAK_OPTS as $opt)
                                            <option value="{{ $opt }}" @selected(($row['dikenal_pasti'] ?? '') === $opt)>{{ $opt }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="kategori_risiko[{{ $i }}][catatan]" class="input" value="{{ $row['catatan'] ?? '' }}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="field mt-3">
                    <label>Ringkasan Dapatan Kategori Risiko</label>
                    <textarea name="ringkasan_kategori_risiko" class="textarea" rows="2">{{ old('ringkasan_kategori_risiko', $report->ringkasan_kategori_risiko) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seksyen 6 — Tahap Risiko & Keutamaan Aset --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">6</span><h3>Tahap Risiko & Keutamaan Aset</h3></div>
                <div data-repeat="aset">
                    <div data-repeat-list>
                        @foreach ($report->aset ?? [] as $i => $row)
                            <div class="repeat-row repeat-row--aset" data-repeat-item>
                                <div class="field"><label>Nama Aset/Sistem</label><input type="text" name="aset[{{ $i }}][nama_aset]" class="input" value="{{ $row['nama_aset'] ?? '' }}"></div>
                                <div class="field"><label>Kategori Risiko</label><input type="text" name="aset[{{ $i }}][kategori_risiko]" class="input" value="{{ $row['kategori_risiko'] ?? '' }}"></div>
                                <div class="field"><label>Tahap Risiko</label>
                                    <select name="aset[{{ $i }}][tahap_risiko]" class="select">
                                        <option value="">—</option>
                                        @foreach (App\Models\Report::TAHAP_OPTS as $opt)<option value="{{ $opt }}" @selected(($row['tahap_risiko'] ?? '') === $opt)>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="field"><label>Keutamaan</label>
                                    <select name="aset[{{ $i }}][keutamaan]" class="select">
                                        <option value="">—</option>
                                        @foreach (App\Models\Report::TAHAP_OPTS as $opt)<option value="{{ $opt }}" @selected(($row['keutamaan'] ?? '') === $opt)>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <div class="field"><label>Catatan</label><input type="text" name="aset[{{ $i }}][catatan]" class="input" value="{{ $row['catatan'] ?? '' }}"></div>
                                <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                            </div>
                        @endforeach
                    </div>
                    <template>
                        <div class="repeat-row repeat-row--aset" data-repeat-item>
                            <div class="field"><label>Nama Aset/Sistem</label><input type="text" name="aset[__INDEX__][nama_aset]" class="input"></div>
                            <div class="field"><label>Kategori Risiko</label><input type="text" name="aset[__INDEX__][kategori_risiko]" class="input"></div>
                            <div class="field"><label>Tahap Risiko</label>
                                <select name="aset[__INDEX__][tahap_risiko]" class="select"><option value="">—</option><option>Tinggi</option><option>Sederhana</option><option>Rendah</option></select>
                            </div>
                            <div class="field"><label>Keutamaan</label>
                                <select name="aset[__INDEX__][keutamaan]" class="select"><option value="">—</option><option>Tinggi</option><option>Sederhana</option><option>Rendah</option></select>
                            </div>
                            <div class="field"><label>Catatan</label><input type="text" name="aset[__INDEX__][catatan]" class="input"></div>
                            <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                        </div>
                    </template>
                    <button type="button" class="btn btn--ghost btn--sm" data-repeat-add>+ Tambah Aset</button>
                </div>
                <div class="field mt-3">
                    <label>Ringkasan Keutamaan Aset</label>
                    <textarea name="ringkasan_keutamaan_aset" class="textarea" rows="2">{{ old('ringkasan_keutamaan_aset', $report->ringkasan_keutamaan_aset) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seksyen 7 — Catatan Analisis & Limitasi Data --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">7</span><h3>Catatan Analisis & Limitasi Data</h3></div>
                <div data-repeat="limitasi">
                    <div data-repeat-list>
                        @foreach ($report->limitasi ?? [] as $i => $row)
                            <div class="repeat-row repeat-row--limitasi" data-repeat-item>
                                <div class="field"><label>Isu / Limitasi</label><input type="text" name="limitasi[{{ $i }}][isu]" class="input" value="{{ $row['isu'] ?? '' }}"></div>
                                <div class="field"><label>Kesan kepada Analisis</label><input type="text" name="limitasi[{{ $i }}][kesan]" class="input" value="{{ $row['kesan'] ?? '' }}"></div>
                                <div class="field"><label>Catatan</label><input type="text" name="limitasi[{{ $i }}][catatan]" class="input" value="{{ $row['catatan'] ?? '' }}"></div>
                                <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                            </div>
                        @endforeach
                    </div>
                    <template>
                        <div class="repeat-row repeat-row--limitasi" data-repeat-item>
                            <div class="field"><label>Isu / Limitasi</label><input type="text" name="limitasi[__INDEX__][isu]" class="input"></div>
                            <div class="field"><label>Kesan kepada Analisis</label><input type="text" name="limitasi[__INDEX__][kesan]" class="input"></div>
                            <div class="field"><label>Catatan</label><input type="text" name="limitasi[__INDEX__][catatan]" class="input"></div>
                            <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                        </div>
                    </template>
                    <button type="button" class="btn btn--ghost btn--sm" data-repeat-add>+ Tambah Limitasi</button>
                </div>
            </div>
        </div>

        {{-- Seksyen 8 — Cadangan Tindakan Susulan --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">8</span><h3>Cadangan Tindakan Susulan</h3></div>
                <div data-repeat="cadangan">
                    <div data-repeat-list>
                        @foreach ($report->cadangan ?? [] as $i => $row)
                            <div class="repeat-row repeat-row--cadangan" data-repeat-item>
                                <div class="field"><label>Cadangan Tindakan</label><input type="text" name="cadangan[{{ $i }}][cadangan]" class="input" value="{{ $row['cadangan'] ?? '' }}"></div>
                                <div class="field"><label>Pihak Berkaitan</label><input type="text" name="cadangan[{{ $i }}][pihak]" class="input" value="{{ $row['pihak'] ?? '' }}"></div>
                                <div class="field"><label>Keutamaan</label>
                                    <select name="cadangan[{{ $i }}][keutamaan]" class="select">
                                        <option value="">—</option>
                                        @foreach (App\Models\Report::TAHAP_OPTS as $opt)<option value="{{ $opt }}" @selected(($row['keutamaan'] ?? '') === $opt)>{{ $opt }}</option>@endforeach
                                    </select>
                                </div>
                                <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                            </div>
                        @endforeach
                    </div>
                    <template>
                        <div class="repeat-row repeat-row--cadangan" data-repeat-item>
                            <div class="field"><label>Cadangan Tindakan</label><input type="text" name="cadangan[__INDEX__][cadangan]" class="input"></div>
                            <div class="field"><label>Pihak Berkaitan</label><input type="text" name="cadangan[__INDEX__][pihak]" class="input"></div>
                            <div class="field"><label>Keutamaan</label>
                                <select name="cadangan[__INDEX__][keutamaan]" class="select"><option value="">—</option><option>Tinggi</option><option>Sederhana</option><option>Rendah</option></select>
                            </div>
                            <button type="button" class="btn btn--ghost btn--sm" data-repeat-remove>Buang</button>
                        </div>
                    </template>
                    <button type="button" class="btn btn--ghost btn--sm" data-repeat-add>+ Tambah Cadangan</button>
                </div>
            </div>
        </div>

        {{-- Seksyen 9 — Kesimpulan --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">9</span><h3>Kesimpulan</h3></div>
                <div class="field">
                    <textarea name="kesimpulan" class="textarea" rows="3">{{ old('kesimpulan', $report->kesimpulan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Seksyen 10 — Pengesahan (maklumat sahaja) --}}
        <div class="card section-block">
            <div class="card__body">
                <div class="section-block__head"><span class="section-no">10</span><h3>Pengesahan Laporan</h3></div>
                <p class="muted">Aliran pengesahan dikendalikan melalui status laporan: <strong>Draf → Dihantar → Diluluskan</strong>. Pegawai Penyelaras Analisis akan menyemak laporan selepas dihantar. Nama pelulus rasmi akan dipaparkan pada PDF.</p>
            </div>
        </div>

        <div class="toolbar">
            <button type="submit" class="btn btn--primary">{{ $mode === 'create' ? 'Simpan Draf' : 'Kemas Kini' }}</button>
            <a href="{{ $mode === 'create' ? route('reports.index') : route('reports.show', $report) }}" class="btn btn--ghost">Batal</a>
        </div>
    </form>
@endsection
