<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Private;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Private\AuthMeResource;
use Illuminate\Http\Response;

/**
 * @tag Auth
 */
class AuthMeController extends Controller
{
    /**
     * @authenticated
     */
    public function show(): AuthMeResource
    {
        $user = auth()->user();

        // @status 200
        return new AuthMeResource($user, __('success'), Response::HTTP_OK);
    }
}
