<?php

namespace App\Models;

use App\Enums\ArticleServiceEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'url',
        'content',
        'author',
        'service',
        'extra_data',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'timestamp',
            'extra_data' => 'collection',
            'service' => ArticleServiceEnum::class,
        ];
    }
}
