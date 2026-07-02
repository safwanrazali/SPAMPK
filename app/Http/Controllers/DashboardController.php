<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if ($user->isPentadbir()) {
            return $this->pentadbirDashboard($user);
        }

        if ($user->isAnalisis()) {
            return $this->analisisDashboard($user);
        }

        // Pengurusan & Pegawai Penyelaras Analisis — papan pemuka belum dimuktamadkan.
        return $this->genericDashboard($user);
    }

    /**
     * Pentadbir Sistem tidak terlibat dalam penyediaan laporan,
     * jadi papan pemuka mereka fokus kepada data pengguna sistem.
     */
    private function pentadbirDashboard(User $user): View
    {
        $stats = [
            'jumlah' => User::count(),
            'aktif' => User::where('is_active', true)->count(),
            'tidak_aktif' => User::where('is_active', false)->count(),
        ];

        $mengikutPeranan = collect(User::ROLES)->map(fn ($label, $key) => [
            'label' => $label,
            'jumlah' => User::where('role', $key)->count(),
        ])->values();

        $terkini = User::latest()->limit(5)->get();

        return view('dashboard.pentadbir', compact('user', 'stats', 'mengikutPeranan', 'terkini'));
    }

    /**
     * Pegawai Analisis melihat status laporan yang mereka sediakan sendiri.
     */
    private function analisisDashboard(User $user): View
    {
        $base = Report::where('created_by', $user->id);

        $stats = [
            'jumlah' => (clone $base)->count(),
            'draf' => (clone $base)->where('workflow_status', Report::STATUS_DRAF)->count(),
            'dihantar' => (clone $base)->where('workflow_status', Report::STATUS_DIHANTAR)->count(),
            'diluluskan' => (clone $base)->where('workflow_status', Report::STATUS_DILULUSKAN)->count(),
            'pembetulan' => (clone $base)->where('workflow_status', Report::STATUS_PEMBETULAN)->count(),
        ];

        $terkini = (clone $base)->latest()->limit(5)->get();

        return view('dashboard.analisis', compact('user', 'stats', 'terkini'));
    }

    /**
     * Placeholder sementara untuk Pengurusan & Pegawai Penyelaras Analisis
     * sehingga papan pemuka khusus mereka dimuktamadkan.
     */
    private function genericDashboard(User $user): View
    {
        $base = Report::query();

        $stats = [
            'jumlah' => (clone $base)->count(),
            'draf' => (clone $base)->where('workflow_status', Report::STATUS_DRAF)->count(),
            'dihantar' => (clone $base)->where('workflow_status', Report::STATUS_DIHANTAR)->count(),
            'diluluskan' => (clone $base)->where('workflow_status', Report::STATUS_DILULUSKAN)->count(),
            'pembetulan' => (clone $base)->where('workflow_status', Report::STATUS_PEMBETULAN)->count(),
        ];

        $terkini = (clone $base)->latest()->limit(5)->get();

        return view('dashboard', compact('user', 'stats', 'terkini'));
    }
}