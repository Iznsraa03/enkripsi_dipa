<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreMataKuliahRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'kode_mk'  => ['required', 'string', 'max:20', 'unique:mata_kuliahs,kode_mk'],
            'nama_mk'  => ['required', 'string', 'max:255'],
            'sks'      => ['required', 'integer', 'min:1', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:14'],
            'jenis'    => ['required', 'string', 'in:wajib,pilihan'],
        ];
    }
}
