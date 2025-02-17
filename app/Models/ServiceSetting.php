<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleServiceEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'service_name',
        'is_active',
        'update_interval',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'service_name' => ArticleServiceEnum::class,
            'is_active' => 'boolean',
            'last_updated_at' => 'datetime',
        ];
    }
}
