<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Private;

use App\Models\User;
use App\Traits\ApiResourceBaseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class AuthMeResource extends JsonResource
{
    use ApiResourceBaseTrait;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
