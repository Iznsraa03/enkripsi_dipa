<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\EncryptedCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model Ipk.
 *
 * Field `ipk` disimpan ter-enkripsi AES-256-GCM.
 *
 * @property int    $id
 * @property int    $mahasiswa_id
 * @property string $ipk         (auto-decrypted, e.g. "3.92")
 * @property int    $total_sks
 */
final class Ipk extends Model
{
    use HasFactory;

    protected $table = 'ipks';

    protected $fillable = [
        'mahasiswa_id',
        'ipk',
        'total_sks',
    ];

    /**
     * Enkripsi IPK menggunakan AES-256-GCM.
     */
    protected $casts = [
        'ipk'       => EncryptedCast::class,
        'total_sks' => 'integer',
    ];

    // ----- Relationships -----

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Menghitung ulang IPK mahasiswa secara otomatis berdasarkan nilainya.
     */
    public static function recalculateForMahasiswa(int $mahasiswaId): void
    {
        $mahasiswa = Mahasiswa::with('nilais.mataKuliah')->find($mahasiswaId);
        
        if (!$mahasiswa) {
            return;
        }

        $nilais = $mahasiswa->nilais;

        $totalSks = 0;
        $weightedSum = 0.0;

        $gradeWeights = [
            'A'  => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B'  => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C'  => 2.0,
            'D'  => 1.0,
            'E'  => 0.0,
        ];

        foreach ($nilais as $nilai) {
            $sks = $nilai->mataKuliah->sks;
            $grade = strtoupper($nilai->grade);
            $weight = $gradeWeights[$grade] ?? 0.0;

            $totalSks += $sks;
            $weightedSum += $weight * $sks;
        }

        $ipkVal = $totalSks > 0 ? round($weightedSum / $totalSks, 2) : 0.0;

        self::updateOrCreate(
            ['mahasiswa_id' => $mahasiswaId],
            [
                'ipk' => number_format($ipkVal, 2),
                'total_sks' => $totalSks
            ]
        );
    }
}
