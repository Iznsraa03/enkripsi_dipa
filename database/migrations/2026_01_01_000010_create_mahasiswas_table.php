<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel mahasiswas — data sensitif di-enkripsi AES-256-GCM.
 * Field: nama, email, alamat, nomor_telepon disimpan sebagai JSON ciphertext.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            // Encrypted PII fields (AES-256-GCM JSON: {iv, tag, data})
            $table->text('nama')->comment('AES-256-GCM encrypted');
            $table->text('email')->comment('AES-256-GCM encrypted');
            $table->text('alamat')->nullable()->comment('AES-256-GCM encrypted');
            $table->text('nomor_telepon')->nullable()->comment('AES-256-GCM encrypted');
            // Plaintext academic fields
            $table->foreignId('jurusan_id')->constrained('jurusans')->cascadeOnDelete();
            $table->unsignedTinyInteger('semester')->default(1);
            $table->string('angkatan', 4);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
