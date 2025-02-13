<?php

declare(strict_types=1);

namespace App\Services\NewsApiServices;

use App\Enums\ArticleServiceEnum;
use App\Services\Contracts\ArticleService;
use App\Services\DTO\ArticleData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianService extends ArticleService
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key');
        $this->apiUrl = rtrim(config('services.guardian.url'), '/');
    }

    public function fetchArticles(array $queryParams = []): array
    {
        try {
            $response = Http::retry(3, 100)
                ->timeout(5)
                ->get("{$this->apiUrl}/search", [
                    'api-key' => $this->apiKey,
                    'show-fields' => 'all',
                    'page-size' => 50,
                    ...$queryParams,
                ]);

            if ($response->ok()) {
                $data = $response->json();

                if (! isset($data['response']['results']) || ! is_array($data['response']['results'] ?? [])) {
                    Log::error('Invalid Guardian API response', ['response' => $data]);

                    return [];
                }

                return $data['response']['results'];
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
            'id' => $article['id'] ?? null,
            'sectionId' => $article['sectionId'] ?? null,
            'sectionName' => $article['sectionName'] ?? null,
            'thumbnail' => $article['fields']['thumbnail'] ?? null,
        ];

        return new ArticleData(
            title: $article['webTitle'] ?? '',
            url: $article['webUrl'] ?? '',
            description: $article['fields']['trailText'] ?? '',
            service: ArticleServiceEnum::THE_GUARDIAN,
            content: $article['fields']['bodyText'] ?? '',
            author: $article['fields']['byline'] ?? '',
            extra_data: $extraData,
            published_at: isset($article['webPublicationDate']) ? Carbon::parse($article['webPublicationDate']) : null,
        );
    }
}
