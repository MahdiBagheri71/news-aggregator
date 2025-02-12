<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ArticleServiceEnum;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'description' => $this->faker->text(),
            'url' => $this->faker->url(),
            'content' => $this->faker->text(),
            'author' => $this->faker->word(),
            'service' => $this->faker->randomElement(ArticleServiceEnum::getAllValues()),
            'extra_data' => collect([]),
            'published_at' => $this->faker->dateTime(),
        ];
    }
}
