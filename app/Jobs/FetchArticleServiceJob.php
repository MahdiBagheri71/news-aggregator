<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ServiceSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FetchArticleServiceJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function backoff(): array
    {
        return [1, 5, 10];
    }

    public function __construct(private readonly ServiceSetting $serviceSetting)
    {
        $this->afterCommit();
    }

    public function uniqueId(): string
    {
        return (string) $this->serviceSetting->id;
    }

    public function handle(): void
    {
        \DB::transaction(function () {

            $setting = $this->serviceSetting;
            $serviceName = $setting->service_name;
            $service = $serviceName?->serviceClass();

            if (isset($serviceName,$service) && $setting->is_active) {
                $lastUpdated = $setting->last_updated_at;
                $updateInterval = $setting->update_interval;

                if ($lastUpdated === null || $lastUpdated->diffInMinutes(now()) >= $updateInterval) {
                    Log::info("Fetching articles from {$serviceName?->value}...");
                    $service->saveArticles();
                    $setting->last_updated_at = Carbon::now();
                    $setting->save();
                    Log::info("Articles from {$serviceName?->value} fetched and saved successfully.");
                } else {
                    Log::warning("Service {$serviceName?->value} is not due for update yet.");
                }
            } else {
                Log::warning("Service {$serviceName?->value} is not active.");
            }
        }, 5);
    }
}
