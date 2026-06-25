<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        // $this->route('mahasiswa') might be the Mahasiswa model
        $mahasiswa = $this->route('mahasiswa');
        $userId = $mahasiswa ? $mahasiswa->user_id : null;

        return [
            // User Data
            'nim'           => ['required', 'string', 'max:20', 'unique:users,nim,' . $userId],
            'password'      => ['nullable', 'string', 'min:8'], // Optional
            
            // Mahasiswa Data
            'nama'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'alamat'        => ['nullable', 'string'],
            'nomor_telepon' => ['nullable', 'string', 'max:30'],
            'program_studi' => ['required', 'string', 'max:100'],
            'semester'      => ['required', 'integer', 'min:1', 'max:14'],
            'angkatan'      => ['required', 'string', 'size:4'],
        ];
    }
}
