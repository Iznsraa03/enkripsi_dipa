<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreNilaiRequest extends FormRequest
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
            'nilai_angka'    => ['required', 'numeric', 'min:0', 'max:100'],
            'semester_tahun' => ['required', 'string', 'max:20'],
        ];
    }
}
