<?php

declare(strict_types=1);

namespace App\Services\SpatieQueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class FullTextSearchArticleFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        return $query->whereFullText(['title', 'description', 'content'], $value, ['expanded' => true, 'mode' => 'boolean']);
    }
}
