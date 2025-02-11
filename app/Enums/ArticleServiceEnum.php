<?php

declare(strict_types=1);

namespace App\Enums;

use App\Traits\BaseEnum;

enum ArticleServiceEnum: string
{
    use BaseEnum;
    case NEWS_API = 'NewsApi';
    case OPEN_NEWS = 'OpenNews';
    case NEWS_CRED = 'NewsCred';
    case THE_GUARDIAN = 'The Guardian';
    case BBC_NEWS = 'BBC News';
}
