<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * LoginRequest — Validasi form login SIAKAD.
 *
 * Memvalidasi NIM (format numerik) dan password sebelum AuthController memprosesnya.
 */
final class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nim'      => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nim.required'  => 'NIM atau Username wajib diisi.',
            'nim.min'       => 'NIM atau Username minimal 3 karakter.',
            'nim.max'       => 'NIM atau Username maksimal 20 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min'  => 'Password minimal 6 karakter.',
        ];
    }
}
