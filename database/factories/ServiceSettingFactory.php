<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ServiceSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceSettingFactory extends Factory
{
    protected $model = ServiceSetting::class;

    public function definition(): array
    {
        return [
            'service_name' => $this->faker->name(),
            'is_active' => $this->faker->boolean(),
            'update_interval' => $this->faker->randomNumber(),
            'last_updated_at' => $this->faker->dateTime(),
        ];
    }
}
