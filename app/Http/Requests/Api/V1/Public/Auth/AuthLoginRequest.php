<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Public\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
                'min:6',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
