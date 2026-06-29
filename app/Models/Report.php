<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    /* ---------- Status aliran kerja ---------- */
    public const STATUS_DRAF = 'draf';
    public const STATUS_DIHANTAR = 'dihantar';
    public const STATUS_DILULUSKAN = 'diluluskan';
    public const STATUS_PEMBETULAN = 'perlu_pembetulan';

    public const WORKFLOW = [
        self::STATUS_DRAF => 'Draf',
        self::STATUS_DIHANTAR => 'Dihantar (Menunggu Semakan)',
        self::STATUS_DILULUSKAN => 'Diluluskan',
        self::STATUS_PEMBETULAN => 'Perlu Pembetulan',
    ];

    /* ---------- Pilihan tetap (selaras templat rasmi) ---------- */
    public const STATUS_ANALISIS_OPTS = ['Selesai', 'Selesai dengan catatan', 'Perlu tindakan lanjut'];
    public const STATUS_DATA_OPTS = ['Lengkap', 'Tidak Lengkap', 'Tiada'];
    public const YA_TIDAK_OPTS = ['Ya', 'Tidak'];
    public const TAHAP_OPTS = ['Tinggi', 'Sederhana', 'Rendah'];

    /* Seksyen 3 — 7 komponen data diterima */
    public const KOMPONEN_DATA = [
        'Jadual 0: Inventory',
        'Jadual 1: SBOM',
        'Jadual 2: CBOM',
        'Jadual 3: Risk Register',
        'Jadual 4: Risk Assessment',
        'Pelan Migrasi',
        'Keperluan Sumber',
    ];

    /* Seksyen 4 — 6 dapatan inventori */
    public const DAPATAN_INVENTORI = [
        'Bilangan aset / sistem yang dianalisis',
        'Komponen kriptografi yang dikenal pasti',
        'Algoritma kriptografi utama yang digunakan',
        'Protokol / saluran komunikasi berkaitan',
        'Vendor / kebergantungan utama',
        'Isu inventori yang dikenal pasti',
    ];

    /* Seksyen 5 — 5 kategori risiko */
    public const KATEGORI_RISIKO = [
        'Pendedahan Algoritma Berisiko Kuantum',
        'Pendedahan Protokol Kriptografi Berisiko Kuantum',
        'Kekangan Pergantungan Komponen Kriptografi',
        'Kekangan Kelincahan Kriptografi',
        'Pendedahan Data Sulit Jangka Panjang',
    ];

    public const TUJUAN_LALAI = 'Laporan ini disediakan bagi membentangkan dapatan analisis data migrasi Kriptografi Pasca-Kuantum (PQC) bagi entiti/agensi berkenaan berdasarkan data yang diterima daripada NACSA di bawah Arahan Ketua Eksekutif NACSA No. 9.';

    protected $fillable = [
        'kod_rujukan', 'nama_agensi', 'sektor', 'tarikh_laporan',
        'disediakan_oleh', 'disemak_oleh', 'status_analisis', 'tujuan',
        'status_data', 'ringkasan_status_data',
        'inventori',
        'kategori_risiko', 'ringkasan_kategori_risiko',
        'aset', 'ringkasan_keutamaan_aset',
        'limitasi', 'cadangan', 'kesimpulan',
        'workflow_status', 'created_by', 'reviewed_by', 'catatan_semakan',
    ];

    protected function casts(): array
    {
        return [
            'tarikh_laporan' => 'date',
            'status_data' => 'array',
            'inventori' => 'array',
            'kategori_risiko' => 'array',
            'aset' => 'array',
            'limitasi' => 'array',
            'cadangan' => 'array',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function workflowLabel(): string
    {
        return self::WORKFLOW[$this->workflow_status] ?? $this->workflow_status;
    }

    public function workflowBadge(): string
    {
        return match ($this->workflow_status) {
            self::STATUS_DRAF => 'badge--muted',
            self::STATUS_DIHANTAR => 'badge--info',
            self::STATUS_DILULUSKAN => 'badge--success',
            self::STATUS_PEMBETULAN => 'badge--warning',
            default => 'badge--muted',
        };
    }

    /* ---------- Pembina struktur lalai bagi seksyen tetap ---------- */

    public static function defaultStatusData(): array
    {
        return array_map(fn ($k) => ['komponen' => $k, 'status' => '', 'catatan' => ''], self::KOMPONEN_DATA);
    }

    public static function defaultInventori(): array
    {
        return array_map(fn ($k) => ['dapatan' => $k, 'catatan' => ''], self::DAPATAN_INVENTORI);
    }

    public static function defaultKategoriRisiko(): array
    {
        return array_map(fn ($k) => ['kategori' => $k, 'dikenal_pasti' => '', 'catatan' => ''], self::KATEGORI_RISIKO);
    }

    /* ---------- Penjana kod rujukan: MPQ-LAPORAN-{AGENSI}-{Ymd}-{NNN} ---------- */
    public static function generateKodRujukan(string $agensi): string
    {
        $slug = Str::of($agensi)->upper()->replaceMatches('/[^A-Z0-9]+/', '')->limit(8, '')->value();
        $slug = $slug !== '' ? $slug : 'AGENSI';
        $tarikh = now()->format('Ymd');
        $seq = self::whereDate('created_at', now()->toDateString())->count() + 1;

        return sprintf('MPQ-LAPORAN-%s-%s-%03d', $slug, $tarikh, $seq);
    }
}
