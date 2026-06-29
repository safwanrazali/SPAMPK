<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Report::with('author')->latest();
        if ($user->isAnalisis()) {
            $query->where('created_by', $user->id);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('workflow_status', $status);
        }

        $reports = $query->paginate(12)->withQueryString();

        return view('reports.index', compact('reports'));
    }

    public function create(): View
    {
        $this->authorize('create', Report::class);

        $report = new Report([
            'tujuan' => Report::TUJUAN_LALAI,
            'status_data' => Report::defaultStatusData(),
            'inventori' => Report::defaultInventori(),
            'kategori_risiko' => Report::defaultKategoriRisiko(),
            'aset' => [],
            'limitasi' => [],
            'cadangan' => [],
        ]);

        return view('reports.form', [
            'report' => $report,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Report::class);

        $data = $this->validateReport($request);

        $report = new Report($data);
        $report->kod_rujukan = Report::generateKodRujukan($data['nama_agensi']);
        $report->workflow_status = Report::STATUS_DRAF;
        $report->created_by = $request->user()->id;
        $report->save();

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berjaya dicipta sebagai draf. Kod rujukan: '.$report->kod_rujukan);
    }

    public function show(Report $report): View
    {
        $this->authorize('view', $report);

        return view('reports.show', compact('report'));
    }

    public function edit(Report $report): View
    {
        $this->authorize('update', $report);

        // pastikan struktur seksyen tetap sentiasa lengkap
        $report->status_data = $report->status_data ?: Report::defaultStatusData();
        $report->inventori = $report->inventori ?: Report::defaultInventori();
        $report->kategori_risiko = $report->kategori_risiko ?: Report::defaultKategoriRisiko();

        return view('reports.form', [
            'report' => $report,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('update', $report);

        $report->fill($this->validateReport($request));
        $report->save();

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berjaya dikemas kini.');
    }

    public function submit(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('submit', $report);

        $report->workflow_status = Report::STATUS_DIHANTAR;
        $report->disediakan_oleh = $report->disediakan_oleh ?: $request->user()->name;
        $report->catatan_semakan = null;
        $report->save();

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan telah dihantar untuk semakan Pegawai Penyelaras Analisis.');
    }

    public function review(Request $request, Report $report): RedirectResponse
    {
        $this->authorize('review', $report);

        $validated = $request->validate([
            'keputusan' => ['required', Rule::in(['lulus', 'pembetulan'])],
            'catatan_semakan' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validated['keputusan'] === 'lulus') {
            $report->workflow_status = Report::STATUS_DILULUSKAN;
            $report->disemak_oleh = $request->user()->name;
            $message = 'Laporan telah disahkan dan diluluskan.';
        } else {
            $report->workflow_status = Report::STATUS_PEMBETULAN;
            $message = 'Laporan dikembalikan kepada Pegawai Analisis untuk pembetulan.';
        }

        $report->reviewed_by = $request->user()->id;
        $report->catatan_semakan = $validated['catatan_semakan'] ?? null;
        $report->save();

        return redirect()->route('reports.show', $report)->with('success', $message);
    }

    public function pdf(Report $report): Response
    {
        $this->authorize('view', $report);

        $pdf = Pdf::loadView('reports.pdf', compact('report'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($report->kod_rujukan.'.pdf');
    }

    public function destroy(Report $report): RedirectResponse
    {
        $this->authorize('delete', $report);
        $report->delete();

        return redirect()->route('reports.index')->with('success', 'Laporan telah dipadam.');
    }

    /* ---------------- Pengesahan & penyusunan data 10 seksyen ---------------- */

    private function validateReport(Request $request): array
    {
        $validated = $request->validate([
            'nama_agensi' => ['required', 'string', 'max:255'],
            'sektor' => ['nullable', 'string', 'max:255'],
            'tarikh_laporan' => ['required', 'date'],
            'disediakan_oleh' => ['nullable', 'string', 'max:255'],
            'disemak_oleh' => ['nullable', 'string', 'max:255'],
            'status_analisis' => ['required', Rule::in(Report::STATUS_ANALISIS_OPTS)],
            'tujuan' => ['nullable', 'string'],
            'ringkasan_status_data' => ['nullable', 'string'],
            'ringkasan_kategori_risiko' => ['nullable', 'string'],
            'ringkasan_keutamaan_aset' => ['nullable', 'string'],
            'kesimpulan' => ['nullable', 'string'],
            // seksyen tetap (status & catatan sahaja diterima dari input)
            'status_data' => ['array'],
            'inventori' => ['array'],
            'kategori_risiko' => ['array'],
            // seksyen pelbagai baris
            'aset' => ['array'],
            'limitasi' => ['array'],
            'cadangan' => ['array'],
        ], [], [
            'nama_agensi' => 'nama agensi',
            'tarikh_laporan' => 'tarikh laporan',
            'status_analisis' => 'status analisis',
        ]);

        // Seksyen 3 — Status Data Diterima (label dijamin dari pelayan)
        $validated['status_data'] = collect(Report::KOMPONEN_DATA)->map(function ($komponen, $i) use ($request) {
            return [
                'komponen' => $komponen,
                'status' => (string) $request->input("status_data.$i.status", ''),
                'catatan' => (string) $request->input("status_data.$i.catatan", ''),
            ];
        })->all();

        // Seksyen 4 — Ringkasan Dapatan Inventori
        $validated['inventori'] = collect(Report::DAPATAN_INVENTORI)->map(function ($dapatan, $i) use ($request) {
            return [
                'dapatan' => $dapatan,
                'catatan' => (string) $request->input("inventori.$i.catatan", ''),
            ];
        })->all();

        // Seksyen 5 — Kategori Risiko
        $validated['kategori_risiko'] = collect(Report::KATEGORI_RISIKO)->map(function ($kategori, $i) use ($request) {
            return [
                'kategori' => $kategori,
                'dikenal_pasti' => (string) $request->input("kategori_risiko.$i.dikenal_pasti", ''),
                'catatan' => (string) $request->input("kategori_risiko.$i.catatan", ''),
            ];
        })->all();

        // Seksyen 6 — Tahap Risiko & Keutamaan Aset (pelbagai baris)
        $validated['aset'] = $this->cleanRows($request->input('aset', []), ['nama_aset', 'kategori_risiko', 'tahap_risiko', 'keutamaan', 'catatan']);

        // Seksyen 7 — Catatan & Limitasi
        $validated['limitasi'] = $this->cleanRows($request->input('limitasi', []), ['isu', 'kesan', 'catatan']);

        // Seksyen 8 — Cadangan Tindakan Susulan
        $validated['cadangan'] = $this->cleanRows($request->input('cadangan', []), ['cadangan', 'pihak', 'keutamaan']);

        return $validated;
    }

    private function cleanRows(array $rows, array $keys): array
    {
        return collect($rows)
            ->map(function ($row) use ($keys) {
                $out = [];
                foreach ($keys as $k) {
                    $out[$k] = is_array($row) ? (string) ($row[$k] ?? '') : '';
                }

                return $out;
            })
            ->filter(fn ($row) => collect($row)->some(fn ($v) => trim($v) !== ''))
            ->values()
            ->all();
    }
}
