<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

final class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nim'      => ['required', 'string', 'max:20', 'unique:users,nim'],
            'password' => ['required', 'string', 'min:8'],
            'role'     => ['required', 'in:admin,mahasiswa'],
        ];
    }
}
