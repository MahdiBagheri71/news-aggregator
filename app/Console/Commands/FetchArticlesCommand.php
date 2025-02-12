<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\FetchArticleServiceJob;
use App\Models\ServiceSetting;
use Illuminate\Console\Command;

class FetchArticlesCommand extends Command
{
    protected $signature = 'articles:fetch';

    protected $description = 'Fetch latest articles from API';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {

        $this->info('Start : Fetching latest articles from API');

        ServiceSetting::query()
            ->latest('last_updated_at')
            ->chunk(10, function ($serviceSettings) {
                foreach ($serviceSettings as $setting) {
                    FetchArticleServiceJob::dispatchSync($setting);
                }
            });

        $this->info('End : Fetched latest articles from API');
    }
}
