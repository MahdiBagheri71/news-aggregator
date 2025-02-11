<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Public\Auth;

use App\Models\User;
use App\Traits\ApiResourceBase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class AuthLoginResource extends JsonResource
{
    use ApiResourceBase;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'token' => $this->token ?? null,
        ];
    }
}
