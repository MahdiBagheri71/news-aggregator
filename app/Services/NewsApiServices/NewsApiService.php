<?php

declare(strict_types=1);

namespace App\Services\NewsApiServices;

use App\Enums\ArticleServiceEnum;
use App\Services\Contracts\ArticleService;
use App\Services\DTO\ArticleData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class NewsApiService extends ArticleService
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.news_api.key');
        $this->apiUrl = rtrim(config('services.news_api.url'), '/');
    }

    public function fetchArticles(array $queryParams = []): array
    {
        try {
            $response = Http::get("{$this->apiUrl}/everything", [
                'apiKey' => $this->apiKey,
                'q' => 'latest-news',
                ...$queryParams,
            ]);

            if ($response->ok()) {
                $data = $response->json();

                return $data['articles'] ?? [];
            }

            return [];
        } catch (\Throwable $throwable) {
            \Log::error($throwable->getMessage());
        }

        return [];
    }

    public function getData(array $article): ArticleData
    {
        $extraData = [
            'source' => $article['source'] ?? null,
            'urlToImage' => $article['urlToImage'] ?? null,
        ];

        return new ArticleData(
            title: $article['title'] ?? '',
            url: $article['url'] ?? '',
            description: $article['description'] ?? '',
            service: ArticleServiceEnum::NEWS_API,
            content: $article['content'] ?? '',
            author: $article['author'] ?? '',
            extra_data: $extraData,
            published_at: $article['publishedAt'] ? Carbon::parse($article['publishedAt']) : null,
        );
    }
}
