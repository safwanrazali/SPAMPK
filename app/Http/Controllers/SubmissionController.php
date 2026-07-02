<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class SubmissionController extends Controller
{
    public function index(): View
    {
        // Senarai penyerahan contoh sementara — akan digantikan dengan data sebenar.
        $submissions = [
            ['rujukan' => 'PSR-0001', 'agensi' => 'Agensi Contoh A', 'status' => 'Menunggu Semakan', 'tarikh' => now()->subDays(2)],
            ['rujukan' => 'PSR-0002', 'agensi' => 'Agensi Contoh B', 'status' => 'Diluluskan', 'tarikh' => now()->subDays(5)],
        ];

        return view('submissions.index', compact('submissions'));
    }
}