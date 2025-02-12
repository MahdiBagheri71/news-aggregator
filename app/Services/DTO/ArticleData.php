<?php

declare(strict_types=1);

namespace App\Services\DTO;

use App\Enums\ArticleServiceEnum;
use Illuminate\Support\Carbon;

class ArticleData
{
    public function __construct(
        public string $title,
        public string $url,
        public string $description,
        public ArticleServiceEnum $service,
        public ?string $content,
        public ?string $author,
        public ?array $extra_data,
        public ?Carbon $published_at
    ) {}

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'service' => $this->service,
            'content' => $this->content ?? null,
            'author' => $this->author ?? null,
            'extra_data' => $this->extra_data ?? [],
            'published_at' => $this->published_at ?? null,
        ];
    }
}
