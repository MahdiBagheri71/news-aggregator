<?php

declare(strict_types=1);

namespace App\Services\Api\Actions;

use App\Models\User;
use App\Services\Api\DTO\AuthLoginDTO;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthLoginAction
{
    public function handle(AuthLoginDTO $data): User
    {
        $user = User::query()
            ->where('email', $data->email)
            ->firstOrFail();

        // @status 401
        abort_if(
            ! Hash::check($data->password, $user->password),
            Response::HTTP_UNAUTHORIZED,
            __('Invalid credentials.')
        );

        $user->token = $user->createToken('auth_token')->plainTextToken;

        return $user;
    }
}
