<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SystemConfigController extends Controller
{
    public function index(): View
    {
        // Tetapan contoh sementara — akan digantikan dengan konfigurasi sebenar.
        $settings = [
            ['label' => 'Nama Sistem', 'nilai' => config('app.name')],
            ['label' => 'Zon Waktu', 'nilai' => config('app.timezone')],
            ['label' => 'Bahasa Lalai', 'nilai' => strtoupper(config('app.locale'))],
            ['label' => 'Mod Nyahpepijat', 'nilai' => config('app.debug') ? 'Aktif' : 'Tidak Aktif'],
        ];

        return view('config.index', compact('settings'));
    }
}