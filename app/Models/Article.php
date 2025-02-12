<?php

namespace App\Models;

use App\Enums\ArticleServiceEnum;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
            'published_at' => 'datetime',
            'extra_data' => 'collection',
            'service' => ArticleServiceEnum::class,
        ];
    }

    public function scopePublishedAtBefore(Builder $query, $date): Builder
    {
        return $query->where('published_at', '<=', Carbon::parse($date));
    }

    public function scopePublishedAtAfter(Builder $query, $date): Builder
    {
        return $query->where('published_at', '>=', Carbon::parse($date));
    }
}
