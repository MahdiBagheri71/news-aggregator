<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ArticleServiceEnum;
use App\Models\ServiceSetting;
use Illuminate\Database\Seeder;

class ServiceSettingSeeder extends Seeder
{
    public function run(): void
    {

        collect([
            [
                'service_name' => ArticleServiceEnum::NEWS_API,
                'is_active' => true,
                'update_interval' => 5,
            ],
            [
                'service_name' => ArticleServiceEnum::THE_GUARDIAN,
                'is_active' => true,
                'update_interval' => 5,
            ],
            [
                'service_name' => ArticleServiceEnum::BBC_NEWS,
                'is_active' => true,
                'update_interval' => 5,
            ],
        ])->each(function (array $setting) {
            ServiceSetting::updateOrCreate([
                'service_name' => $setting['service_name'],
            ], [
                'is_active' => $setting['is_active'],
                'update_interval' => $setting['update_interval'],
            ]);
        });
    }
}
