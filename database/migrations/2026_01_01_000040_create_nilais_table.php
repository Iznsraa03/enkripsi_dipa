<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel nilais — nilai akademik mahasiswa.
 * Field `nilai_angka` dan `nilai_akhir` di-enkripsi AES-256-GCM.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained()->cascadeOnDelete();
            // Encrypted grade data
            $table->text('nilai_angka')->comment('AES-256-GCM encrypted numeric grade (0-100)');
            $table->string('grade', 3)->comment('Letter grade (A, B+, B, etc.)');
            $table->string('semester_tahun', 20)->comment('e.g. 2024/2025 Ganjil');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
