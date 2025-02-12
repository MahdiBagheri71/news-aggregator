<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Private\Article;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Article */
class ArticleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->when($this?->id, $this?->id),
            'title' => $this->when($this?->title, $this?->title),
            'description' => $this->when($this?->description, $this?->description),
            'url' => $this->when($this?->url, $this?->url),
            'content' => $this->when($this?->content, $this?->content),
            'author' => $this->when($this?->author, $this?->author),
            'extra_data' => $this->when($this?->extra_data ?? null, $this?->extra_data),
            'published_at' => $this->when($this?->published_at ?? null, $this?->published_at),
        ];
    }
}
