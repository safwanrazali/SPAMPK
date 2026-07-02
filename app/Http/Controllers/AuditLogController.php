<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(): View
    {
        // Data contoh sementara — akan digantikan dengan rekod log audit sebenar.
        $logs = [
            ['masa' => now()->subMinutes(12), 'pengguna' => 'admin', 'tindakan' => 'Log masuk ke sistem', 'butiran' => '—'],
            ['masa' => now()->subHours(2), 'pengguna' => 'analisis', 'tindakan' => 'Mencipta laporan baharu', 'butiran' => 'MPQ-LAPORAN-...'],
            ['masa' => now()->subDay(), 'pengguna' => 'penyelaras', 'tindakan' => 'Meluluskan laporan', 'butiran' => 'MPQ-LAPORAN-...'],
        ];

        return view('audit.index', compact('logs'));
    }
}