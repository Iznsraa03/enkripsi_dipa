<?php

declare(strict_types=1);

namespace App\Casts;

use App\Services\EncryptionService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

/**
 * EncryptedCast — Custom Eloquent Cast untuk AES-256-GCM.
 *
 * Mengimplementasikan otomasi enkripsi/dekripsi di layer Eloquent Model.
 * Setiap field yang menggunakan cast ini akan:
 *  - Otomatis ter-enkripsi saat `set` (simpan ke DB)
 *  - Otomatis ter-dekripsi saat `get` (baca dari DB)
 *
 * Penggunaan di Model:
 * ```php
 * protected $casts = [
 *     'nama'  => EncryptedCast::class,
 *     'email' => EncryptedCast::class,
 * ];
 * ```
 */
final class EncryptedCast implements CastsAttributes
{
    public function __construct(
        private readonly ?EncryptionService $encryptionService = null,
    ) {}

    private function service(): EncryptionService
    {
        return $this->encryptionService ?? app(EncryptionService::class);
    }

    /**
     * Dekripsi nilai dari database saat dibaca.
     *
     * @param  Model  $model
     * @param  string $key
     * @param  mixed  $value  JSON payload dari database
     * @param  array<string, mixed> $attributes
     * @return string|null
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        // MODE SIMULASI: skip dekripsi — kembalikan nilai mentah dari DB
        if (config('app.simulation') === true || config('database.default') === 'mysql_simulation') {
            return (string) $value;
        }

        try {
            return $this->service()->decrypt($value);
        } catch (\Throwable $e) {
            // Log untuk audit trail tanpa expose plaintext
            logger()->warning("EncryptedCast: Gagal dekripsi field `{$key}`", [
                'model' => $model::class,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Enkripsi nilai sebelum disimpan ke database.
     *
     * @param  Model  $model
     * @param  string $key
     * @param  mixed  $value  Plaintext yang akan di-enkripsi
     * @param  array<string, mixed> $attributes
     * @return string|null    JSON payload AES-256-GCM
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        // MODE SIMULASI: skip enkripsi — simpan nilai plaintext ke DB
        if (config('app.simulation') === true || config('database.default') === 'mysql_simulation') {
            return (string) $value;
        }

        return $this->service()->encrypt((string) $value);
    }
}
