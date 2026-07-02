<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FileUploadController extends Controller
{
    public function index(): View
    {
        // Senarai fail contoh sementara — akan digantikan dengan data muat naik sebenar.
        $files = [
            ['nama' => 'inventori_kripto.xlsx', 'jenis' => 'XLSX', 'saiz' => '482 KB', 'dimuat_naik' => now()->subDays(1)],
            ['nama' => 'sbom_sistem_a.docx', 'jenis' => 'DOCX', 'saiz' => '128 KB', 'dimuat_naik' => now()->subDays(3)],
        ];

        return view('uploads.index', compact('files'));
    }
}