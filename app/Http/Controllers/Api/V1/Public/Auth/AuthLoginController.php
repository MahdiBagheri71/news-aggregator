<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Public\Auth\AuthLoginRequest;
use App\Http\Resources\Api\V1\Public\Auth\AuthLoginResource;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * @tag Auth
 */
class AuthLoginController extends Controller
{
    /**
     * @unauthenticated
     */
    public function show(AuthLoginRequest $request): AuthLoginResource
    {
        $user = User::query()
            ->where('email', $request->string('email'))
            ->firstOrFail();

        // @status 401
        abort_if(
            ! Hash::check($request->input('password'),
                $user->password), Response::HTTP_UNAUTHORIZED,
            __('Invalid credentials.')
        );

        $user->token = $user->createToken('auth_token')->plainTextToken;

        // @status 200
        return new AuthLoginResource($user, __('Login successful.'), Response::HTTP_OK);

    }
}
