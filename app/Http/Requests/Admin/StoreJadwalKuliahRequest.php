<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreJadwalKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id'   => ['required', 'exists:mahasiswas,id'],
            'mata_kuliah_id' => ['required', 'exists:mata_kuliahs,id'],
            'hari'           => ['required', 'string', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'jam_mulai'      => ['required', 'date_format:H:i'],
            'jam_selesai'    => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruangan_id'    => ['required', 'exists:ruangans,id'],
            'dosen_id'      => ['required', 'exists:dosens,id'],
        ];
    }
}
