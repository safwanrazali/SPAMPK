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

        $base = Report::query();
        if ($user->isAnalisis()) {
            $base->where('created_by', $user->id);
        }

        $stats = [
            'jumlah' => (clone $base)->count(),
            'draf' => (clone $base)->where('workflow_status', Report::STATUS_DRAF)->count(),
            'dihantar' => (clone $base)->where('workflow_status', Report::STATUS_DIHANTAR)->count(),
            'diluluskan' => (clone $base)->where('workflow_status', Report::STATUS_DILULUSKAN)->count(),
            'pembetulan' => (clone $base)->where('workflow_status', Report::STATUS_PEMBETULAN)->count(),
        ];

        $jumlahPengguna = $user->isPentadbir() ? User::count() : null;

        $terkini = (clone $base)->latest()->limit(5)->get();

        return view('dashboard', compact('user', 'stats', 'jumlahPengguna', 'terkini'));
    }
}
