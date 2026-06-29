<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('kod_rujukan')->unique();

            // Seksyen 1 - Maklumat Laporan
            $table->string('nama_agensi');
            $table->string('sektor')->nullable();
            $table->date('tarikh_laporan');
            $table->string('disediakan_oleh')->nullable();
            $table->string('disemak_oleh')->nullable();
            $table->string('status_analisis')->nullable();

            // Seksyen 2 - Tujuan
            $table->text('tujuan')->nullable();

            // Seksyen 3 - Status Data Diterima (7 komponen)
            $table->json('status_data')->nullable();
            $table->text('ringkasan_status_data')->nullable();

            // Seksyen 4 - Ringkasan Dapatan Inventori Kriptografi (6 dapatan)
            $table->json('inventori')->nullable();

            // Seksyen 5 - Dapatan Kategori Risiko Migrasi PQC (5 kategori)
            $table->json('kategori_risiko')->nullable();
            $table->text('ringkasan_kategori_risiko')->nullable();

            // Seksyen 6 - Tahap Risiko & Keutamaan Aset (pelbagai baris)
            $table->json('aset')->nullable();
            $table->text('ringkasan_keutamaan_aset')->nullable();

            // Seksyen 7 - Catatan Analisis & Limitasi Data
            $table->json('limitasi')->nullable();

            // Seksyen 8 - Cadangan Tindakan Susulan
            $table->json('cadangan')->nullable();

            // Seksyen 9 - Kesimpulan
            $table->text('kesimpulan')->nullable();

            // Seksyen 10 + aliran kerja
            $table->string('workflow_status')->default('draf');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->text('catatan_semakan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
