<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\EncryptedCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Model Mahasiswa.
 *
 * Data PII disimpan ter-enkripsi AES-256-GCM via EncryptedCast.
 * Akses field `nama`, `email`, `alamat`, `nomor_telepon` akan otomatis
 * dekripsi tanpa perlu memanggil EncryptionService secara manual.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $nama           (auto-decrypted)
 * @property string $email          (auto-decrypted)
 * @property string|null $alamat    (auto-decrypted)
 * @property string|null $nomor_telepon (auto-decrypted)
 * @property string $program_studi
 * @property int    $semester
 * @property string $angkatan
 */
final class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'alamat',
        'nomor_telepon',
        'program_studi',
        'semester',
        'angkatan',
    ];

    /**
     * Otomatis enkripsi/dekripsi field PII menggunakan AES-256-GCM.
     */
    protected $casts = [
        'nama'          => EncryptedCast::class,
        'email'         => EncryptedCast::class,
        'alamat'        => EncryptedCast::class,
        'nomor_telepon' => EncryptedCast::class,
        'semester'      => 'integer',
    ];

    // ----- Relationships -----

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    public function ipk(): HasOne
    {
        return $this->hasOne(Ipk::class);
    }

    // ----- Scopes -----

    public function scopeByProdi($query, string $prodi): void
    {
        $query->where('program_studi', $prodi);
    }
}
