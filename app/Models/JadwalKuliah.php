<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model JadwalKuliah.
 *
 * @property int    $id
 * @property int    $mata_kuliah_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string $ruangan
 * @property string $dosen
 */
final class JadwalKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'dosen',
    ];

    // ----- Relationships -----

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
