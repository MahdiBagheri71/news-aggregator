<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Private\Article;

use App\Traits\ApiResourceBase;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ArticleCollection extends ResourceCollection
{
    use ApiResourceBase;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
