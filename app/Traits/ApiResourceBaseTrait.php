<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ApiResourceBaseTrait
{
    public function __construct(
        $resource,
        protected ?string $withMessage = null,
        protected int $withStatus = ResponseAlias::HTTP_OK
    ) {
        parent::__construct($resource);
    }

    public function with(Request $request): array
    {
        return [
            'message' => $this->withMessage,
            'status' => $this->withStatus,
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     */
    public function withResponse(Request $request, \Illuminate\Http\JsonResponse $response): void
    {
        $response->setStatusCode($this->withStatus);
    }
}
