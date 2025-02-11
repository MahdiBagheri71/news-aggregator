<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Public\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Public\Auth\AuthLoginRequest;
use App\Http\Resources\Api\V1\Public\Auth\AuthLoginResource;
use App\Services\Api\Actions\AuthLoginAction;
use App\Services\Api\DTO\AuthLoginDTO;
use Illuminate\Http\Response;

/**
 * @tag Auth
 */
class AuthLoginController extends Controller
{
    /**
     * @unauthenticated
     */
    public function show(AuthLoginRequest $request, AuthLoginAction $authLoginAction): AuthLoginResource
    {
        $user = $authLoginAction->handle(AuthLoginDTO::fromArray($request->toArray()));

        // @status 200
        return new AuthLoginResource($user, __('Login successful.'), Response::HTTP_OK);

    }
}
