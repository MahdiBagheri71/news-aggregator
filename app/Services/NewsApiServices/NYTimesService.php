<?php

declare(strict_types=1);

namespace App\Services\NewsApiServices;

use App\Enums\ArticleServiceEnum;
use App\Services\Contracts\ArticleService;
use App\Services\DTO\ArticleData;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NYTimesService extends ArticleService
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.ny_times.key');
        $this->apiUrl = rtrim(config('services.ny_times.url'), '/');
    }

    public function fetchArticles(array $queryParams = []): array
    {
        try {
            $response = Http::retry(3, 100)
                ->timeout(5)
                ->get("{$this->apiUrl}/search/v2/articlesearch.json", [
                    'api-key' => $this->apiKey,
                    'sort' => 'newest',
                    'fl' => 'headline,web_url,abstract,pub_date,byline,multimedia,section_name,snippet',
                    ...$queryParams,
                ]);

            if ($response->ok()) {
                $data = $response->json();

                if (! isset($data['response']['docs']) || ! is_array($data['response']['docs'])) {
                    Log::error('Invalid NYTimes API response', ['response' => $data]);

                    return [];
                }

                return $data['response']['docs'];
            }

            if ($response->status() === 429) {
                Log::error('NYTimes API rate limit exceeded', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                ]);
            }

            return [];
        } catch (\Throwable $throwable) {
            Log::error('NYTimes API error: '.$throwable->getMessage(), [
                'exception' => get_class($throwable),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
            ]);
        }

        return [];
    }

    public function getData(array $article): ArticleData
    {
        // Get the largest image if available
        $image = null;
        if (! empty($article['multimedia'])) {
            $largest = collect($article['multimedia'])
                ->sortByDesc('width')
                ->first();

            if ($largest) {
                $image = 'https://www.nytimes.com/'.$largest['url'];
            }
        }

        $extraData = [
            'section' => $article['section_name'] ?? null,
            'image' => $image,
            'type' => $article['type_of_material'] ?? null,
            'word_count' => $article['word_count'] ?? null,
        ];

        return new ArticleData(
            title: $article['headline']['main'] ?? '',
            url: $article['web_url'] ?? '',
            description: $article['abstract'] ?? $article['snippet'] ?? '',
            service: ArticleServiceEnum::NY_TIMES,
            content: $article['lead_paragraph'] ?? '',
            author: $article['byline']['original'] ?? '',
            extra_data: $extraData,
            published_at: isset($article['pub_date']) ? Carbon::parse($article['pub_date']) : null,
        );
    }
}
