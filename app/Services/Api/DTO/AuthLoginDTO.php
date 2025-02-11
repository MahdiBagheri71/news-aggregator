<?php

declare(strict_types=1);

namespace App\Services\Api\DTO;

class AuthLoginDTO
{
    public function __construct(
        public string $email,
        public string $password) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }
}
