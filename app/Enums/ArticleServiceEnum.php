<?php

declare(strict_types=1);

namespace App\Enums;

use App\Services\NewsApiServices\GuardianService;
use App\Services\NewsApiServices\NewsApiService;
use App\Traits\BaseEnum;

enum ArticleServiceEnum: string
{
    use BaseEnum;
    case NEWS_API = 'NewsApi';
    case OPEN_NEWS = 'OpenNews';
    case NEWS_CRED = 'NewsCred';
    case THE_GUARDIAN = 'The Guardian';
    case BBC_NEWS = 'BBC News';

    public function getArticleService()
    {
        return match ($this) {
            self::NEWS_API => app(NewsApiService::class),
            self::THE_GUARDIAN => app(GuardianService::class),
            default => throw new \RuntimeException("Article service not implemented for {$this->value}"),
        };
    }
}
