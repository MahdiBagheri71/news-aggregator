<?php

declare(strict_types=1);

namespace App\Services\NewsApiServices;

use App\Enums\ArticleServiceEnum;
use App\Services\Contracts\ArticleService;
use App\Services\DTO\ArticleData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BBCNewsService extends ArticleService
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.bbc.key');
        $this->apiUrl = rtrim(config('services.bbc.url'), '/');
    }

    public function fetchArticles(array $queryParams = []): array
    {
        try {
            $response = Http::retry(3, 100)
                ->timeout(5)
                ->withHeaders([
                    'X-Api-Key' => $this->apiKey,
                ])
                ->get("{$this->apiUrl}/articles", [
                    'sources' => 'bbc-news',
                    'language' => 'en',
                    ...$queryParams,
                ]);

            if ($response->ok()) {
                $data = $response->json();

                if (! isset($data['articles']) || ! is_array($data['articles'])) {
                    Log::error('Invalid BBC API response', ['response' => $data]);

                    return [];
                }

                return $data['articles'];
            }

            return [];
        } catch (\Throwable $throwable) {
            Log::error($throwable->getMessage());
        }

        return [];
    }

    public function getData(array $article): ArticleData
    {
        $extraData = [
            'source' => $article['source'] ?? null,
            'category' => $article['category'] ?? null,
            'image' => $article['urlToImage'] ?? null,
        ];

        return new ArticleData(
            title: $article['title'] ?? '',
            url: $article['url'] ?? '',
            description: $article['description'] ?? '',
            service: ArticleServiceEnum::BBC_NEWS,
            content: $article['content'] ?? '',
            author: $article['author'] ?? '',
            extra_data: $extraData,
            published_at: isset($article['publishedAt']) ? Carbon::parse($article['publishedAt']) : null,
        );
    }
}
