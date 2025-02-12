<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Models\Article;
use App\Services\DTO\ArticleData;

abstract class ArticleService
{
    abstract public function fetchArticles(array $queryParams = []): array;

    abstract public function getData(array $article): ArticleData;

    public function saveArticles(): void
    {
        $articles = $this->fetchArticles();

        foreach ($articles as $article) {

            $articleData = $this->getData($article)->toArray();

            Article::updateOrCreate([
                'url' => $articleData['url'],
            ], [
                'title' => $articleData['title'],
                'description' => $articleData['description'],
                'content' => $articleData['content'],
                'published_at' => $articleData['published_at'],
                'extra_data' => $articleData['extra_data'],
                'author' => $articleData['author'],
                'service' => $articleData['service'],
            ]);
        }
    }
}
