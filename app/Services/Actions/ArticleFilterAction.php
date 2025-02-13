<?php

declare(strict_types=1);

namespace App\Services\Actions;

use App\Models\Article;
use Illuminate\Contracts\Pagination\Paginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleFilterAction
{
    public function handle(array $data): Paginator
    {
        return QueryBuilder::for(Article::class)
            ->defaultSort('-published_at')
            ->allowedFields([
                'id',
                'title',
                'description',
                'url',
                'published_at',
                'author',
                'content',
                'extra_data',
            ])
            ->allowedSorts([
                'published_at',
                'title',
                'created_at',
            ])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::scope('published_at_before'),
                AllowedFilter::scope('published_at_after'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('description'),
                AllowedFilter::partial('content'),
            ])
            ->simplePaginate(perPage: $data['per_page'] ?? 15, page: $data['page'] ?? 1);
    }
}
