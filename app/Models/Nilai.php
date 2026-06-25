<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\EncryptedCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Nilai.
 *
 * Field `nilai_angka` disimpan ter-enkripsi AES-256-GCM.
 * Grade huruf disimpan plaintext untuk kemudahan querying.
 *
 * @property int    $id
 * @property int    $mahasiswa_id
 * @property int    $mata_kuliah_id
 * @property string $nilai_angka     (auto-decrypted, e.g. "87.5")
 * @property string $grade           (e.g. "A", "B+")
 * @property string $semester_tahun
 */
final class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'nilai_angka',
        'grade',
        'semester_tahun',
    ];

    /**
     * Enkripsi nilai_angka menggunakan AES-256-GCM.
     */
    protected $casts = [
        'nilai_angka' => EncryptedCast::class,
    ];

    // ----- Relationships -----

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
