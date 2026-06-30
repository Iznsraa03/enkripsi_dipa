<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model User — Akun autentikasi SIAKAD.
 *
 * Login menggunakan `nim` (bukan email).
 * Password di-hash menggunakan Argon2id (via Laravel Hash facade).
 *
 * @property int    $id
 * @property string $nim
 * @property string $password  (Argon2id hash)
 * @property string $role      (admin|mahasiswa)
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'nim',
        'password',
        'pin',
        'role',
    ];

    protected $hidden = [
        'password',
        'pin',
        'remember_token',
    ];

    /**
     * Password cast — menggunakan Argon2id (dikonfigurasi di config/hashing.php).
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'pin'      => \App\Casts\EncryptedCast::class,
        ];
    }

    // ----- Relationships -----

    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }

    // ----- Helpers -----

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
