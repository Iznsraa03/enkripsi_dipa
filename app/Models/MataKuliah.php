<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model MataKuliah.
 *
 * @property int    $id
 * @property string $kode_mk
 * @property string $nama_mk
 * @property int    $sks
 * @property int    $semester
 * @property string $jenis
 */
final class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
    ];

    protected $casts = [
        'sks'      => 'integer',
        'semester' => 'integer',
    ];

    // ----- Relationships -----

    public function jadwals(): HasMany
    {
        return $this->hasMany(JadwalKuliah::class);
    }

    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}
