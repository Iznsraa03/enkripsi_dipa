<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel ipks — Indeks Prestasi Kumulatif mahasiswa.
 * Field `ipk` di-enkripsi AES-256-GCM untuk melindungi data sensitif.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ipks', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained()->cascadeOnDelete();
            $table->text('ipk')->comment('AES-256-GCM encrypted IPK value');
            $table->unsignedSmallInteger('total_sks')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ipks');
    }
};
