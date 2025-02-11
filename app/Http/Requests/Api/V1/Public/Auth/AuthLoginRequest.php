<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1\Public\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            /**
             * @example admin@test.com
             *
             * @default admin@test.com
             */
            'email' => [
                'required',
                'string',
                'email',
            ],
            /**
             * @example password
             *
             * @default password
             */
            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
