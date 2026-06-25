<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

/**
 * EncryptionService — AES-256-GCM Implementation.
 *
 * Mengimplementasikan enkripsi simetris menggunakan cipher AES-256-GCM
 * sesuai metodologi penelitian skripsi.
 *
 * Setiap operasi enkripsi menghasilkan JSON payload dengan:
 *   - `iv`  : 12-byte nonce (base64) — unik per operasi
 *   - `tag` : 16-byte authentication tag (base64) — untuk integritas data
 *   - `data`: ciphertext (base64) — data terenkripsi
 *
 * @see https://www.php.net/manual/en/function.openssl-encrypt.php
 */
final class EncryptionService
{
    private readonly string $key;
    private const CIPHER    = 'aes-256-gcm';
    private const IV_LENGTH = 12; // NIST recommended for GCM
    private const TAG_LENGTH = 16;

    public function __construct()
    {
        $keyBase64 = config('app.aes_encryption_key');

        if (empty($keyBase64)) {
            throw new RuntimeException('AES_ENCRYPTION_KEY is not set in environment.');
        }

        $this->key = base64_decode($keyBase64, strict: true);

        if (strlen($this->key) !== 32) {
            throw new RuntimeException('AES_ENCRYPTION_KEY must decode to exactly 32 bytes for AES-256.');
        }
    }

    /**
     * Enkripsi plaintext menggunakan AES-256-GCM.
     *
     * @param  string $plaintext Data yang akan dienkripsi
     * @return string JSON payload: {"iv":"...","tag":"...","data":"..."}
     *
     * @throws RuntimeException jika enkripsi gagal
     */
    public function encrypt(string $plaintext): string
    {
        $iv  = random_bytes(self::IV_LENGTH);
        $tag = '';

        $ciphertext = openssl_encrypt(
            data:       $plaintext,
            cipher_algo: self::CIPHER,
            passphrase: $this->key,
            options:    OPENSSL_RAW_DATA,
            iv:         $iv,
            tag:        $tag,
            tag_length: self::TAG_LENGTH,
        );

        if ($ciphertext === false) {
            throw new RuntimeException('AES-256-GCM enkripsi gagal: ' . openssl_error_string());
        }

        return json_encode([
            'iv'   => base64_encode($iv),
            'tag'  => base64_encode($tag),
            'data' => base64_encode($ciphertext),
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * Dekripsi JSON payload AES-256-GCM.
     *
     * @param  string $payload JSON yang dihasilkan oleh encrypt()
     * @return string Plaintext asli
     *
     * @throws RuntimeException jika dekripsi gagal atau data telah dimanipulasi
     */
    public function decrypt(string $payload): string
    {
        $decoded = json_decode($payload, associative: true, flags: JSON_THROW_ON_ERROR);

        if (! isset($decoded['iv'], $decoded['tag'], $decoded['data'])) {
            throw new RuntimeException('Format payload enkripsi tidak valid.');
        }

        $iv         = base64_decode($decoded['iv'], strict: true);
        $tag        = base64_decode($decoded['tag'], strict: true);
        $ciphertext = base64_decode($decoded['data'], strict: true);

        $plaintext = openssl_decrypt(
            data:        $ciphertext,
            cipher_algo: self::CIPHER,
            passphrase:  $this->key,
            options:     OPENSSL_RAW_DATA,
            iv:          $iv,
            tag:         $tag,
        );

        if ($plaintext === false) {
            throw new RuntimeException(
                'Dekripsi AES-256-GCM gagal — kemungkinan data telah dimanipulasi (GCM tag mismatch).'
            );
        }

        return $plaintext;
    }

    /**
     * Cek apakah string adalah JSON payload enkripsi yang valid.
     */
    public function isEncrypted(string $value): bool
    {
        try {
            $decoded = json_decode($value, associative: true, flags: JSON_THROW_ON_ERROR);
            return isset($decoded['iv'], $decoded['tag'], $decoded['data']);
        } catch (\Throwable) {
            return false;
        }
    }
}
